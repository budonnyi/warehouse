<?php

use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'File';
$this->params['breadcrumbs'][] = $this->title;
$no = 1;
?>

<div class="content-wrapper" style=" overflow:scroll">
<div class="body-content animated fadeIn">
    <div class="row">
        <div class="col-sm-12 col-md-12">

            <!-- Start Summernote 5 WYSIWYG Editor -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title"><span class="icon"><i class="glyphicon glyphicon-list-alt"></i></span>&nbsp;&nbsp;Files</h3>
                    </div>

                    <div class="clearfix"></div>
                </div><!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="table-responsive rounded mb-20 col-sm-12">
                        <a href="<?= Url::to(['create']); ?>" class="btn btn-success btn-md rounded"><span class="icon"><i class="fa fa-plus"></i>&nbsp;</span><strong>Upload File</strong></a>
                        <br /><br />

                        <table id="tour-16" class="table table-striped table-theme">
                            <thead>
                            <tr>
                                <th class="text-center border-right">No</th>
                                <th class="text-center">File</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if($files):?>
                                <?php foreach($files as $file):?>
                                    <tr>
                                        <td class="text-center border-right"><?= $no; ?></td>
                                        <td class="text-center">
                                            <?php if(!empty($file->filename)):?>
                                                <img src="<?= Yii::$app->urlManager->baseUrl.'/files/'.$file->filename;?>"/>
                                            <?php endif;?>
                                        </td>
                                        <td class="text-center">
                                            <a data-toggle="tooltip" title="update" href="<?= Url::to(['update','id'=>$file->id]); ?>" class="btn btn-primary btn-xs rounded"><strong>Update</strong></a>
                                            <a data-toggle="tooltip" title="delete" href="<?= Url::to(['delete','id'=>$file->id]); ?>" class="btn btn-danger btn-xs rounded"><strong>Delete</strong></a>
                                        </td>
                                        <?php $no++;?>
                                    </tr>
                                <?php endforeach;?>
                            <?php else : ?>
                                <tr>
                                    <td colspan=6 class="text-center text-green"><strong>Tidak ada file yang telah ditambahkan</strong></td>
                                </tr>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
            <!--/ End Summernote 5 WYSIWYG Editor -->

        </div>
    </div><!-- /.row -->

</div>
</div>