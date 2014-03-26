<?php 
class SphinxComponent extends Object {
    /**
     * Used for runtime configuration of model
     */
    static $_defaults = array('server' => 'localhost', 'port' => 3312);

    /**
     * Spinx client object
     *
     * @var SphinxClient
     */
    static $sphinx = null;

    function initialize($controller, $config = array()){
        $settings = array_merge((array)$config,self::$_defaults);
        
        App::import('Vendor', 'sphinxapi');
        
        self::$sphinx = new SphinxClient();
        self::$sphinx->SetServer($settings['server'], $settings['port']);
        
    }

    function search($query){print_r($query);exit;
        if(!isset(self::$sphinx)){
            self::initialize(null);
        }
        
        if (empty($query['limit'])){
            $query['limit'] = 9999999;
            $query['page'] = 1;
        }

        foreach ($query as $key => $setting){
                        
            switch ($key){
                case 'filter':
                    foreach ($setting as $key2=>$arg){
                        self::$sphinx->SetFilter($key2, (array)$arg );
                    }
                    break;
                case 'filterRange':
                    //TODO
                    break;
                case 'filterFloatRange':
                    $method = 'Set' . $key;
                    foreach ($setting as $arg){
                        $arg[3] = empty($arg[3]) ? false : $arg[3];
                        self::$sphinx->{$method}($arg[0], (array)$arg[1], $arg[2], $arg[3]);
                    }
                    break;
                case 'matchMode':
                    self::$sphinx->SetMatchMode($setting);
                    break;
                case 'sortMode':
                    self::$sphinx->SetSortMode(key($setting), reset($setting));
                    break;
                case 'fieldWeights':
                    self::$sphinx->SetFieldWeights($setting);
                    break;
                case 'rankingMode':
                    self::$sphinx->SetRankingMode($setting);
                    break;
                case 'setGeoAnchor':
                    if (!isset($setting['latField'])) $setting['latField']='lat';
                    if (!isset($setting['lngField'])) $setting['lngField']='lng';
                    
                    self::$sphinx->SetGeoAnchor( $setting['latField'], $setting['lngField'],floatval($setting['lat']),floatval($setting['lng']));
                    break;
                case 'groupby' : 
                    foreach ($setting as $args){
                                            
                        if(is_array($args)) {
                            $arg=$args;
                        }else{
                            $arg=array();
                            $arg[0]=$args;
                            $arg[1]="@count desc";
                        }
                        self::$sphinx->SetGroupBy($arg[0], SPH_GROUPBY_ATTR, $arg[1]);
                    }
                    break;
                case 'groupdistinct':
                    foreach ($setting as $arg){
                        self::$sphinx->SetGroupDistinct ($arg);
                    }
                    break;
                default:
                    break;
            }
        }
            
        self::$sphinx->SetLimits(($query['page'] - 1) * $query['limit'],$query['limit']);

        $indexes = !empty($query['index']) ? implode(',' , $query['index']) : '*';

        
        if(!isset($query['search'])){
            $result = self::$sphinx->Query('', $indexes);    
        }else{
            $result = self::$sphinx->Query($query['search'], $indexes);
        }

                        
        if ($result === false){
            
            throw new SphinxException();
        }
        
        return $result;
    }
}

        
class SphinxException extends Exception  { }
?>