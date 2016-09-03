<?php
namespace develnext\bundle\jurl;

use ide\bundle\AbstractBundle;
use ide\bundle\AbstractJarBundle;
use php\io\Stream;
use php\lib\Str;
use ide\project\Project;

/**
 * Class jURLBundle
 */
class jURLBundle extends AbstractJarBundle
{


    public function onAdd(Project $project, AbstractBundle $owner = null)
    {
        parent::onAdd($project, $owner);

        $this->setBootstrap($project);
        $this->installCode();
    }

    public function onRemove(Project $project, AbstractBundle $owner = null)
    {
        parent::onRemove($project, $owner);

        $this->setBootstrap($project);
        $this->removeCode();
    }

    /**
     * Чтоб подтянулись curl_* функции, объявим их 
     * в файле .bootstrap до старта приложения
     */

    private $code = '<?php' . "\n" . 'new \cURL; // Generated by jURL bundle',
            $bootstrap;

    private function setBootstrap(Project $project){
        $this->bootstrap = $project->getRootDir() . '/src/JPHP-INF/.bootstrap';
    }

    private function installCode(){
        $code = $this->getBootstrap();
        $code = Str::Replace($code, '<?php', $this->code);
        $this->putBootstrap($code);
    }

    private function removeCode(){
        $code = $this->getBootstrap();
        $code = Str::Replace($code, $this->code, '<?php');
        $this->putBootstrap($code);
    }

    private function getBootstrap(){
        return Stream::getContents($this->bootstrap);
    }

    private function putBootstrap($content){
        return Stream::putContents($this->bootstrap, $content);
    }



}
