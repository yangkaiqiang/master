<?php
/*
 * Create a PHP CURL class
 *
 * author : 特务Q
 * cTime  : 2018/5/29
 * */

abstract class abstructCURL
{
    /*
     * @var object
     * */
    protected $curl_instance;

    /*
     * @var bool
     * */
    protected $requestData = [];

    /*
     * @var array
     * */
    protected $set_options = [
        'CURLOPT_URL' => '',
        'CURLOPT_RETURNTRANSFER ' => 1,
    ];

    /*
     * init curl
     * */
    public function __construct()
    {
        $this->curl_instance = curl_init();
        $this->start();
    }

    /*
     * set curl option
     *
     * @return bool
     * */
    public function setOption($option = []){

        if(!$option || !is_array($option)){
            return ['code' => 1001, 'msg'=>'set param error'];
        }

        $this->filterOption($option);
    }

    /*
     * filter user set Option
     * */
    private function filterOption($option)
    {
        if($this->requestData){
            $this->set_options['CURLOPT_POST'] = 1;
            $this->set_options['CURLOPT_POSTFIELDS'] = $this->requestData;
        }

        $this->set_options = array_merge($this->set_options, $option);
    }

    /*
     * start
     * */
    public function start()
    {
        if (!curl_setopt_array($this->curl_instance, $this->set_options)){
            return ['code' => 1002, 'msg'=>'curl set error'];
        }

        $this->resultReduction( $this->exec() );
    }

    /*
     * exec request
     *
     * @return data
     * */
    private function exec()
    {
        return curl_exec($this->curl_instance) ?? ['code' => 1003, 'msg'=>'exec error'];
    }

    /*
     *
     * */
    private function resultReduction($data)
    {
        // 检查是否有错误发生
        if(! curl_errno ($this->curl_instance))
        {
            $info  =  curl_getinfo ($this->curl_instance);

            return [
                'code' => 1005,
                'msg' => 'Took '  .  $info [ 'total_time' ] .  ' seconds to send a request to '  .  $info [ 'url' ]
            ];
        }

        return $data;
    }


    /*
     * get request
     * */
    public function get()
    {
    }

    /*
     * post request
     * */
    public function post($data = [])
    {
        $this->requestData = $data;
    }

    /*
     * close curl
     * */
    public function __destruct()
    {
        curl_close($this->curl_instance);
    }
}
