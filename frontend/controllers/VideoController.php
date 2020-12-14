<?php


namespace frontend\controllers;


use common\models\Video;
use common\models\VideoLikes;
use common\models\VideoView;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class VideoController extends Controller
{
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['likes', 'dislike', 'history'],
                'rules' => [
                    [
                    'allow' => true,
                    'roles' => ['@']
                    ]
                ]
            ],
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'likes' => ['post'],
                    'dislike' => ['post'],
                ]
            ]
        ];
    }

    public function actionIndex()
    {
        $this->layout = 'main';
        $dataProvider = new ActiveDataProvider([
            'query' => Video::find()->with('createdBy')->published()->latest(),
            'pagination' => [
                'pageSize' => 8
            ]
        ]);

        return $this->render('index', [
            'dataProvider'=> $dataProvider
        ]);
    }

    public function actionView($id)
    {
        $this->layout = 'blank';
        $video = $this->findVideo($id);

        $videoView = new VideoView();
        $videoView->video_id = $id;
        $videoView->user_id = \Yii::$app->user->id;
        $videoView->created_at = time();
        $videoView->save();

        $similarVideos = Video::find()
            ->published()
            ->andWhere(['NOT', ['video_id' => $id]])
            ->byKeyword($video->title)
            ->limit(10)
            ->all();

        return $this->render('view', [
            'model' => $video,
            'similarVideos' => $similarVideos
        ]);
    }

    public function actionLikes($id)
    {
        $video = $this->findVideo($id);
        $userId = \Yii::$app->user->id;

        $videoLikes = VideoLikes::find()
            ->userIdVideoId($userId, $id)
            ->one();

        if (!$videoLikes) {
            $this->saveLike($id, $userId, VideoLikes::TYPE_LIKE);
        } else if ($videoLikes->type === VideoLikes::TYPE_LIKE) {
            $videoLikes->delete();
        } else {
            $videoLikes->delete();
            $this->saveLike($id, $userId, VideoLikes::TYPE_LIKE);
        }

        return $this->renderAjax('_buttons', [
            'model' => $video
        ]);
    }

    public function actionDislikes($id)
    {
        $video = $this->findVideo($id);
        $userId = \Yii::$app->user->id;

        $videoLikes = VideoLikes::find()
            ->userIdVideoId($userId, $id)
            ->one();

        if (!$videoLikes) {
            $this->saveLike($id, $userId, VideoLikes::TYPE_DISLIKE);
        } else if ($videoLikes->type === VideoLikes::TYPE_DISLIKE) {
            $videoLikes->delete();
        } else {
            $videoLikes->delete();
            $this->saveLike($id, $userId, VideoLikes::TYPE_DISLIKE);
        }

        return $this->renderAjax('_buttons', [
            'model' => $video
        ]);
    }

    public function actionSearch($keyword)
    {
        $this->layout = 'main';
        $query = Video::find()
            ->published()
            ->latest();

        if ($keyword) {
            $query->byKeyword($keyword);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('search', [
            'dataProvider'=> $dataProvider
        ]);

    }

    public function actionHistory()
    {
        $this->layout = 'main';
        $query = Video::find()
            ->alias('v')
            ->innerJoin("(SELECT video_id, MAX(created_at) as max_date FROM video_view
                WHERE user_id = :userId
                GROUP BY video_id) vv", 'vv.video_id = v.video_id', [
                    'userId' => \Yii::$app->user->id
            ])
            ->orderBy("vv.max_date DESC");


        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $this->render('search', [
            'dataProvider'=> $dataProvider
        ]);

    }

    protected function findVideo($id)
    {
        $video = Video::findOne($id);
        if (!$video) {
            throw new NotFoundHttpException("Video does not exist");
        }
        return $video;

    }

    protected function saveLike($videoId, $userId, $type) {
        $videoLikes = new VideoLikes();
        $videoLikes->video_id = $videoId;
        $videoLikes->user_id = $userId;
        $videoLikes->type = $type;
        $videoLikes->created_at = time();
        $videoLikes->save();
    }
}