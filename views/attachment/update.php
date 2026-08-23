<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Update File';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
<div class="body-content animated fadeIn">
    <div class="row">
        <div class="col-sm-12 col-md-12">

            <!-- Start Summernote 5 WYSIWYG Editor -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title"><span class="icon"><i class="glyphicon glyphicon-list-alt"></i></span>&nbsp;&nbsp;Update File</h3>
                    </div>

                    <div class="clearfix"></div>
                </div><!-- /.panel-heading -->
                <div class="panel-body">
                    <?= $this->render('_form',['model'=>$model]);?>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
            <!--/ End Summernote 5 WYSIWYG Editor -->

        </div>
    </div><!-- /.row -->

</div>
        </div>
    </section>
</div>