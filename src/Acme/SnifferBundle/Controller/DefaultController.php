<?php

namespace Acme\SnifferBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    private function getActiveAdapters()
    {
        try {
            $adapter = shell_exec('/sbin/ifconfig -a | sed "s/[ \t].*//;/^\(lo\|\)$/d"');
            $adapter = preg_split ('/$\R?^/m', $adapter);
            return $adapter;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    private function getPackageStatus($package_name)
    {
        try {
            $os = split('="', shell_exec('cat /etc/*-release | grep PRETTY_NAME'));
            $os = split(' ', $os[1]);
            $os = trim(strtolower($os[0]));
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
        
        try {
            if($os == 'debian' || $os == 'ubuntu') {   
                $check_package = shell_exec("dpkg --get-selections | grep $package_name");
                if(!empty($check_package) && $check_package != '') {
                    echo 'Installed';
                }else {
                    echo 'Not Installed';
                }
            }elseif ($os == 'centos' || $os == 'redhat' || $os == 'red hat' || $os == 'fedora') {
                $check_package = shell_exec("rpm -qa | grep $package_name");
                if(!empty($check_package) && $check_package != '') {
                    echo 'Installed';
                }else {
                    echo 'Not Installed';
                }
            }elseif ($os == 'freebsd' || $os == 'openbsd') {
                $check_package = shell_exec("pkg_info | grep $package_name");
                if(!empty($check_package) && $check_package != '') {
                    echo 'Installed';
                }else {
                    echo 'Not Installed';
                }
            }else{
                echo 'Not Supported Detected';
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function indexAction()
    {
        /*
        $check_package = exec('dpkg --get-selections | grep tcpdump');
        if(!empty($check_package) && $check_package != '') {
            echo 'Installed';
        }else {
            echo 'Not Installed';
        }
         */
        
        // return $this->render('AcmeSnifferBundle:Default:index.html.twig', array('name' => $name));
    }
}
