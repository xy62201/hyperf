<?php

declare (strict_types=1);
namespace App\Model;

/**
 * @property int $id 
 * @property int $nid 
 * @property string $option 
 * @property string $value 
 * @property int $add_time 
 * @property int $is_deleted 
 */
class SpidersNovelOption extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'spiders_novel_options';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'int', 'nid' => 'integer', 'add_time' => 'integer', 'is_deleted' => 'integer'];

    const TAGS_OPTION = 'tags'; // 起点标签
    const CUSTOM_TAGS_OPTION = 'custom_tags'; // 起点自定义标签
    const IS_404 = 'q404'; // 起点404
}