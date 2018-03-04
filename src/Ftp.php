<?php
/**
 * Created by PhpStorm.
 * User: colorrabbit
 * Date: 2017/7/24
 * Time: 下午4:39
 */

namespace ColorGallery;

class Ftp
{
    /**
     * @var $conn
     */
    private $conn;

    /**
     * @return mixed
     */
    public function getConn()
    {
        return $this->conn;
    }

    /**
     * @param mixed $conn
     */
    public function setConn($conn)
    {
        $this->conn = $conn;
    }

    public function ftpConnect($host, $port = '')
    {
        if (empty($port)) {
            $this->conn = ftp_connect($host);
        } else {
            $this->conn = ftp_connect($host, $port);
        }

        if (!$this->conn) {
            return 'login fail';
        }

        return 'login success';
    }


    public function ftpLogin($ftpUsername, $ftpPassword)
    {
        $loginResult = ftp_login($this->conn, $ftpUsername, $ftpPassword);
        if (!$loginResult) {
            return 'login fail';
        }
        ftp_pasv($this->conn, true);

        return 'login success';
    }


    public function fptPwd()
    {
        $inDir = ftp_pwd($this->conn);
        if (!$inDir) {
            return 'get dir info fail';
        }

        return $inDir;
    }

    public function ftpList()
    {
        return ftp_nlist($this->conn, $this->fptPwd());
    }

    public function ftpMkdir($dir)
    {
        if (!in_array($this->conn . '/' . $dir, $this->ftpList())) {
            if (!ftp_mkdir($this->conn, $dir)) {
                return 'mkdir fail';
            }
            return 'mkdir ' . $dir . ' success';
        }

        return 'file exists';
    }

    public function fptChdir($dir)
    {
        if (!ftp_chdir($this->conn, $dir)) {
            return 'chdir fail';
        }

        return 'chdir ' . $dir . 'success';
    }

    public function ftpPut($remoteFile, $file)
    {
        if (ftp_put($this->conn, $remoteFile, $file, FTP_ASCII)) {
            return 'Successfully Uploadeed ' . $file;
        }

        return 'There was a problem while uploading ' . $file;
    }

    public function ftpClose()
    {
        ftp_close($this->conn);
    }
}
