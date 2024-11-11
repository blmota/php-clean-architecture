<?php

namespace Source\Framework\Core;

use Source\Framework\Support\Message;

/**
 * Class Controller
 *
 * @package Source\Core
 */
class Controller
{
    /** @var View */
    protected $view;

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
        $this->message = new Message();

        // pega dados enviados como json para a api
        $inputData = file_get_contents('php://input');
        if (!empty($inputData)) {
            $data = json_decode($inputData, true);
            if (is_array($data)) {
                $this->dataJSON = filter_var_array($data, FILTER_DEFAULT);
            }
        }
    }
}
