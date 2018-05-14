<?php

namespace App\Observers;

use App\Handlers\SlugTranslateHandler;
use App\Jobs\TranslateSlug;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{

    public function saving(Topic $topic)
    {
        //xss处理
        $topic->body = clean($topic->body, 'user_topic_body');

        //生成话题摘要
        $topic->excerpt = make_excerpt($topic->body);

        /*if ( ! $topic->slug)
        {
            app(SlugTranslateHandler::class)->translate($topic->title);
        }*/

    }

    /*public function saved(Topic $topic)
    {
        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {

            // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }
    }*/

    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function deleted(Topic $topic)
    {
        //在模型监听器中，数据库操作需要避免再次 Eloquent 事件，所以这里我们使用了 DB 类进行操作
        DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}