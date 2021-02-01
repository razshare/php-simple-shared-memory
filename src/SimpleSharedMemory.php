<?php

class SimpleSharedMemory{
    private static array $refs = [];

    private const MODE_C = 'c';
    private const MODE_W = 'w';
    private const PERMISSION = 0644;
    
    private ?int $size = null;
    private ?int $key = null;

    
    private function id(?int $length = null):?\Shmop{
        if($length === null){
            if($this->size !== null){
                $length = $this->size;
            }else{
                $length = 1;
                $this->size = 1;
            }
        }else{
            $this->size = $length;
        }


        if($this->key)
            $key = $this->key;
        else{
            $object = new \stdClass();
            $key = spl_object_id($object);
            static::$refs[''.$key] = $object;
            $this->key = $key;
        }

        $id = @\shmop_open($key,static::MODE_C,static::PERMISSION,$length);
        if(!$id)
            $id = @\shmop_open($key,static::MODE_W,static::PERMISSION,$length);
        return !$id?null:$id;
    }

    public function persist(mixed $value):bool{
        $serialized = serialize($value);
        $size = \strlen($serialized);
        $this->delete($size);
        $id = $this->id($size);
        if(!$id)
            return null;
        return shmop_write($id,$serialized,0) !== false;
    }

    public function find():mixed{
        $id = $this->id();
        $value = \shmop_read($id,0,$this->size);
        if(!$value) $value = null;
        return \unserialize($value);
    }

    public function delete(?int $length = null, ?\Shmop $id= null):bool{
        if(!$id) 
            $id = $this->id($length);
        return \shmop_delete($id);
    }
}