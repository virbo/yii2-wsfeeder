<?php

namespace virbo\wsfeeder\src;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\client;
use yii\web\BadRequestHttpException;
use yii\helpers\Json;
use yii\web\Response;

/**
 * just simple configuration as component
 *
 * ```
 * 'components' => [
 *      ...
 *      'feeder' => [
 *          'class' => \virbo\wsfeeder\Feeder::class,
 *          'endpoint' => 'http://url_feeder:8082/ws',
 *          'username' => 'username_feeder',
 *          'password' => 'password_feeder'
 *      ],
 *      ...
 * ],
 * ```
 *
 * @author Yusuf Ayuba <yusuf@dutainformasi.net>
 * @since 1.0
 */
class Feeder extends Component
{
    /*
     * @var string endpoint the WS Feeder
     * */
    public $endpoint;

    /*
     * @var string username the WS Feeder
     * */
    public $username;

    /*
     * @var string password the WS Feeder
     * */
    public $password;

    /*
     * @var integer mode the WS Feeder (0 = Live, 1 = Sandbox)
     * */
    public $mode = 0;

    /*
     * @var string content-type the WS Feeder (application/json or application/xml)
     * */
    public $contentType = 'application/json';

    /*
     * @var string method the WS Feeder (post or get)
     * */
    public $method = 'post';

    /**
     * @var client instance
     */
    protected $_client;

    /*
     * @var string token the WS Feeder
     * */
    protected $_token = null;

    public function init()
    {
        $this->_client = new Client([
            'baseUrl' => $this->endpoint
        ]);

        $this->getToken();
    }

    /*
     * Action to Feeder
     * example show dictionary
     *
     * ```
     * public function actionDictionary()
     * {
     *      $data = [
     *          'act' => 'GetDictionary',
     *          'fungsi' => 'InsertNilaiTransferPendidikanMahasiswa'
     *      ];
     *      return Yii::$app->feeder->actFeeder($data);
     * }
     * ```
     *
     * for more method, can see at http://localhost:8082/ws/live2.php or http://localhost:8082/ws/sandbox2.php
     * */
    public function actFeeder($data = [])
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (!is_array($data)) {
            throw new BadRequestHttpException('Data must array');
        }

        $mode = $this->mode == 0 ? 'live2.php' : 'sandbox2.php';

        $token = ['token' => $this->_token];

        $data = ArrayHelper::merge($data, $token);

        $request = $this->_client->createRequest()
            ->setUrl('/'.$mode)
            ->addHeaders(['content-type' => $this->contentType])
            ->setContent(Json::encode($data))
            ->setMethod($this->method)
            ->send();

        //return $request;
        if ($request) {
            if ($request->data['error_code'] == 0) {
                return $request->data;
            } else {
                if ($request->data['error_code'] === 100) {
                    $session = Yii::$app->session;
                    $session['token'] = null;
                    $this->getToken();
                } else {
                    throw new BadRequestHttpException('Error '.$request->data['error_code'].' - '.$request->data['error_desc']);
                }
            }
        } else {
            throw new BadRequestHttpException();
        }
    }

    protected function getToken()
    {
        $session = Yii::$app->session;

        if ($session['token'] === null) {
            $act = Json::encode([
                'act' => 'GetToken',
                'username' => $this->username,
                'password' => $this->password
            ]);

            $mode = $this->mode == 0 ? 'live2.php' : 'sandbox2.php';

            $request = $this->_client->createRequest()
                ->setUrl($mode)
                ->addHeaders(['content-type' => $this->contentType])
                ->setContent($act)
                ->setMethod($this->method)
                ->send();

            if ($request) {
                if ($request->data['error_code'] == 0) {
                    //$this->_token = $request->data['data']['token'];
                    $session['token'] = $request->data['data']['token'];
                } else {
                    throw new BadRequestHttpException('Error '.$request->data['error_code'].' - '.$request->data['error_desc']);
                }
            } else {
                throw new BadRequestHttpException();
            }

            $this->_token = $session['token'];
        } else {
            $this->_token = $session['token'];
        }
    }

}
