<?php
class timer
{
    private $start_time = NULL;
    private $end_time = NULL;

    private function getmicrotime()
    {
      list($usec, $sec) = explode(" ", microtime());
      return ((float)$usec + (float)$sec);
    }

    function start()
    {
      $this->start_time = $this->getmicrotime();
    }

    function stop()
    {
      $this->end_time = $this->getmicrotime();
    }

    function sofar()
    {
        if (is_null($this->start_time))
        {
            exit('Timer: start method not called !');
            return false;
        }

        return round(($this->getmicrotime() - $this->start_time), 4);
    }

    function result()
    {
        if (is_null($this->start_time))
        {
            exit('Timer: start method not called !');
            return false;
        }
        else if (is_null($this->end_time))
        {
            exit('Timer: stop method not called !');
            return false;
        }

        return round(($this->end_time - $this->start_time), 4);
    }

    # an alias of result function
    function time()
    {
        $this->result();
    }

}

function upsertArray($original_array,$new_array){
    echo "--------------------------\r\n";
    echo "------------IN upsertArray--------------\r\n";
    echo "------------original--------------\r\n";
    print_r($original_array);
    echo "------------new_array--------------\r\n";
    print_r($new_array);

    foreach ($new_array as $key => $value) {
        $original_array[$key] = $value;
    }
    return $original_array;
}