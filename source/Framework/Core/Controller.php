<?php

namespace Source\Framework\Core;

use Source\Support\Message;
use Source\Support\Seo;

/**
 * Class Controller
 *
 * @package Source\Core
 */
class Controller
{
    /** @var View */
    protected $view;

    /** @var Seo */
    protected $seo;

    /** @var Message */
    protected $message;

    /** @var array */
    public $dataJSON;

    /**
     * Controller constructor.
     * @param string|null $pathToViews
     */
    public function __construct(string $pathToViews = null)
    {
        $this->view = new View($pathToViews);
        $this->seo = new Seo();
        $this->message = new Message();

        // pega dados enviados como json para a api
        $inputData = file_get_contents('php://input');
        if(!empty($inputData)) {
            $data = json_decode($inputData, true);
            if(is_array($data)) {
                $this->dataJSON = filter_var_array($data, FILTER_DEFAULT);
            }
        }
    }
}