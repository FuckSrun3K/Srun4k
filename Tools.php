<?php
/**
 * Created by PhpStorm.
 * User: skyfire
 * Date: 17-3-19
 * Time: 下午6:58
 */

//信号处理函数
function sig_handler($signo)
{
    switch ($signo) {
        case SIGTERM:
            exit;
        case SIGINT:
            exit;
        case SIGHUP:
            break;
        default:
    }
}

function help_info(&$opt)
{

    $info = <<<HELP
　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　ｘ　　　ｘ　　　　　　　
　ｘｘｘｘｘ　　　　　　　　　　　　　　　　　　　　　　　ｘｘ　　　ｘ　　　　　　　
ｘｘｘ　ｘｘ　　　　　　　　　　　　　　　　　　　　　　ｘｘｘ　　　ｘ　　　　　　　
ｘｘｘｘ　　　　ｘｘｘｘ　ｘ　　　ｘ　ｘｘｘｘｘ　　　ｘｘｘｘ　　　ｘ　ｘｘｘ　　　
　ｘｘｘｘｘ　　ｘｘ　ｘ　ｘ　　　ｘ　ｘｘ　ｘｘ　　ｘｘｘ　ｘ　　　ｘｘｘｘ　　　　
　　　　ｘｘｘ　ｘｘ　　　ｘ　　　ｘ　ｘ　　　ｘ　　ｘｘ　　ｘ　　　ｘｘｘ　　　　　
ｘｘｘ　ｘｘｘ　ｘｘ　　　ｘｘ　ｘｘ　ｘ　　　ｘ　ｘｘｘｘｘｘｘｘ　ｘ　ｘｘ　　　　
ｘｘｘｘｘｘ　　ｘｘ　　　ｘｘｘｘｘ　ｘ　　　ｘ　　　　　　ｘ　　　ｘ　　ｘｘ　　　
　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　ｘ　　　　　　　　　　　
Usage:
  [-u] <username> [-p] <password> [options]

Options:
  -m                          Methods used in operation, Default is 'login',('logout')
  -t                          User login time interval
  -f                          Configure file
  --help                      Help information

HELP;
    if (isset($opt['f']) && file_exists($opt['f']))
    {
        $json_str = file_get_contents($opt['f']);
        $opt = array_merge($opt, json_decode($json_str, true));
    }
    
    if (isset($opt['m']) && $opt['m'] == 'logout')
    {
        Srun4k::logout();
        exit();
    }else if (!isset($opt['u'] ) || !isset($opt['p']))
    {
        echo "ERROR: You need to set the username and password!!!".PHP_EOL.$info;
        exit();
    }else if (isset($opt['help']))
    {
        echo $info;
    }

}