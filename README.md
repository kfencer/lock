# lock
Exclusive &amp; Read/Write locking based on MySQL Locking Service 

## Requrements

Need to install the locking service UDFs as describe in MySQL docs: 

https://dev.mysql.com/doc/refman/5.7/en/locking-service-udf-interface.html

## Examples

- Inject PDO object.


    AbstractDb::setPdo(
        new \PDO($dsn, $user, $password)
    );
    
- Define and use your own lock class based on TeamA\Lock\AbstractDBExclusive or TeamA\Lock\AbstractDBExtended.

- Exclusive lock example:


    class Point extends AbstractDbExclusive
    {
        protected function __construct(
            int      $providerId, 
            ? string $providerPointId, 
            ? string $providerPointEssentialId
        )
        {
            parent::__construct([
                $providerId, $providerPointId, $providerPointEssentialId
            ]);
        }
    }
    
    /* ... */
    
    $pointLock = new Point($pId, $pPointId, null);
    
    try {
        $pointLock->lock();
        
        $this->_db->beginTransaction();
        
        // do smth.
        
        $this->_db->commit();
        
        return true;  
                           
      } catch (TeamA\Lock\TimeoutException $e) {
      
         return false;
         
      } catch (\Exception $e) {
      
         $this->_db->rollback();
         throw $e;
         
      } finally {
      
         $pointLock->releaseIfLocked();
         
      } 
          
- Read/Write locking example:   
    
    
    class GeoBinding extends AbstractDbExtended
    {
        public function __construct(int $departureProviderId)
        {
            parent::__construct(func_get_args());
        }
    }  
    
    /* ... */
    
    $lock = new GeoBinding(static::_getProviderId());
    
    try {
        $lock->lockWrite();
        
        $this->_db->beginTransaction();
        
        // do smth.
        
        $this->_db->commit();
        
        return true;  
                         
    } catch (TeamA\Lock\TimeoutException $e) {
    
       return false;
       
    } catch (\Exception $e) {
    
       $this->_db->rollback();
       throw $e;
       
    } finally {
    
       $lock->release();
       
    }   
    
    /* ... */
    
    $lock = new GeoBinding(static::_getProviderId());
        
    try {
        $lock->lockRead();
        
        $this->_db->beginTransaction();
        
        // do smth. else...  
        
        
       
