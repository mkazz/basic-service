<?

namespace MKaczorowski\BasicService\DAOs;

class BaseDAO {

    protected 
        $entity,
        $dbal;

    public function __construct($dbal, $entity) {
        $this->dbal   = $dbal;
        $this->entity = $entity;
    }
}

