<?php
namespace app\model;

use think\Model;

class HistoryModel extends Model
{
    protected $pk = 'id';
    protected $table = 'lh_history';
    protected $autoWriteTimestamp = false;
}