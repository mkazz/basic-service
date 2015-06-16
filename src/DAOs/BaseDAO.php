<?

namespace MKaczorowski\BasicService\DAOs;

class BasicDAO {

    protected 
        $entity,
        $dbal;

    public function __construct($dbal, $entity) {
        $this->dbal   = $dbal;
        $this->entity = $entity;
    }
}

