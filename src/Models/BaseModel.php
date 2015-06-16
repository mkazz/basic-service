<?

namespace MKaczorowski\BasicService\Models;

class BaseModel {

    protected $app;

    public function __construct(Application $app) {
        $this->app = $app;
    }
}

