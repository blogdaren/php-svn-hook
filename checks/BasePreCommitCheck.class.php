<?php
abstract class BasePreCommitCheck 
{
    public $svnComment;
    public $globalError = array();
    public $codeError = array();
    public $options = array();

    public function __construct($svnComment='', $repoName = '', $trxNum = '')
    {
        $this->svnComment = $svnComment;
        $this->parseOptions();
        $this->repoName = $repoName;
        $this->trxNum = $trxNum; 
    }

    abstract function getTitle();

    abstract function renderErrorSummary();

    public function runCheck($svnCommitedFiles)
    {
        // Check on the comment
        $result = $this->checkSvnComment($this->svnComment);
        if ($result !== null){
            $this->globalError[] = $result;
        }

        // Check on the files
        $index = 0;
        foreach ($svnCommitedFiles as $filename => $lines)
        {
            //过滤掉不想要检查的目录或文件
            $filterResult = $this->filter($filename);
            if($filterResult) continue;

            //Check the entire content
            if($fileResult = $this->checkFullFile($lines, $filename)){
                $index++;
                $this->globalError[] = "(" . $index . ") " . $fileResult;
            }

            //Check line by line
            foreach ($lines as $pos => $line){
                $result = $this->checkFileLine($filename, $pos, $line);
                if ($result !== null){
                    $this->codeError[$filename.':'.($pos+1)] = $result;
                }
            }
        } 
    }

    public function fail() {
        return count($this->globalError) > 0 || count($this->codeError) > 0;   
    }

    public function checkFile($filename){
    }

    public function checkSvnComment($comment){
    }

    public function checkFileLine($file, $pos, $content){
    }

    public function checkFullFile($lines, $filename){
    }

    public function renderErrorDetail(){
        $details = implode("\n",$this->globalError);
        foreach ($this->codeError as $position => $error){
            $details .= $position . ' ' . $error . "\n";
        }
        return $details;
    }

    public function renderInstructions(){
        return "";
    }

    public function hasOption($name){
        return isset($this->options[$name]);
    }

    public function getOption($name){
        if (!$this->hasOption($name)){
            throw new Exception("Option [$name] does not exist"); 
        }
        return $this->options[$name];
    }

    public function parseOptions() {
        preg_match_all('/\-\-([^\s]+)/', $this->svnComment, $matches);
        foreach ($matches[1] as $option) {
            $option = explode('=', $option);
            $this->options[$option[0]] = isset($option[1]) ? $option[1] : true;
        }
    }

    /**
     * Return extension of a given file
     * @param string $filename
     * @return string or null
     */
    public static function getExtension($filename){
        preg_match("@^.*\.([^\.]*)$@", $filename, $match);
        return isset($match[1]) ? $match[1] : null;
    }

    //过滤掉要忽略的目录或文件
    public function filter($filename){
        $pathinfo = pathinfo($filename);
        $config_skip_file = $this->getSkipFile();
        foreach($config_skip_file as $k => $ignore_dir)
        {
            if(strpos($filename, $ignore_dir) !== false) return true;
        }

        return false;
    }

    //获取要忽略检查的目录或文件
    public function getSkipFile()
    {
        $skip_file = @include(dirname(__DIR__) . "/config/skip.file.php");

        if(!is_array($skip_file)) return array();

        return $skip_file;
    }

}
