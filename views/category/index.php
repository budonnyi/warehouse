<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var app\models\CategorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Категорії товарів';
$this->params['breadcrumbs'][] = $this->title;

$dataProvider->pagination = false;
$records = $dataProvider->getModels();
?>
<style>
:root{--ef-border:#e2e8f0;--ef-radius:6px;--ef-focus-bg:#fffbeb;--ef-focus-border:#f59e0b;}
.ef-wrap{padding:16px;}
.ef-section{background:#fff;border:1px solid var(--ef-border);border-radius:var(--ef-radius);margin-bottom:16px;}
.ef-section-head{display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-bottom:1px solid var(--ef-border);gap:8px;flex-wrap:wrap;}
.ef-section-title{font-size:13px;font-weight:600;color:#374151;margin:0;text-transform:uppercase;letter-spacing:.04em;}
.ef-table{width:100%;border-collapse:collapse;font-size:12px;}
.ef-table thead th{background:#f8fafc;padding:7px 8px;border-bottom:2px solid var(--ef-border);text-align:left;font-weight:600;color:#64748b;white-space:nowrap;}
.ef-table tbody tr{border-bottom:1px solid #f1f5f9;}
.ef-table tbody tr:hover{background:#f8fafc;}
.ef-table tbody tr.ef-changed{background:#fffde7;}
.ef-table tbody tr.ef-saved{background:#f0fdf4;}
.ef-table td{padding:3px 4px;vertical-align:middle;}
.ef-table td.ef-id{color:#94a3b8;font-size:11px;padding:0 8px;white-space:nowrap;text-align:right;}
.ef-table .form-control{border:1px solid transparent;background:transparent;height:28px;padding:2px 6px;font-size:12px;border-radius:3px;width:100%;box-sizing:border-box;}
.ef-table .form-control:focus{outline:none;border-color:var(--ef-focus-border);background:var(--ef-focus-bg);}
.ef-table select.form-control{height:28px;padding:2px 4px;}
.ef-btn{display:inline-flex;align-items:center;gap:5px;padding:5px 14px;border-radius:4px;font-size:12px;font-weight:500;border:none;cursor:pointer;text-decoration:none;line-height:1.4;}
.ef-btn-primary{background:#2563eb;color:#fff;}
.ef-btn-primary:hover{background:#1d4ed8;color:#fff;}
.ef-btn-primary:disabled{background:#93c5fd;cursor:not-allowed;}
.ef-btn-success{background:#16a34a;color:#fff;}
.ef-btn-success:hover{background:#15803d;color:#fff;}
.ef-btn-outline{background:#fff;color:#374151;border:1px solid #d1d5db;}
.ef-btn-outline:hover{background:#f9fafb;}
.ef-search-row{display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end;padding:10px 14px;}
.ef-search-field{display:flex;flex-direction:column;gap:3px;}
.ef-search-field label{font-size:11px;color:#64748b;font-weight:500;}
.ef-search-field input,.ef-search-field select{height:30px;padding:2px 8px;font-size:12px;border:1px solid #d1d5db;border-radius:4px;background:#fff;}
.ef-save-bar{position:sticky;bottom:0;background:#fff;border-top:2px solid var(--ef-border);padding:10px 16px;display:flex;align-items:center;gap:10px;z-index:100;box-shadow:0 -2px 8px rgba(0,0,0,.06);}
.ef-badge{background:#fef08a;color:#713f12;border-radius:10px;padding:1px 7px;font-size:11px;font-weight:700;}
.inv-act{display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:4px;border:1px solid #e2e8f0;background:#fff;color:#64748b;text-decoration:none;}
.inv-act:hover{background:#f1f5f9;color:#374151;}
.inv-act-view:hover{color:#2563eb;border-color:#2563eb;}
.inv-act-edit:hover{color:#16a34a;border-color:#16a34a;}
.inv-act-del:hover{color:#dc2626;border-color:#dc2626;}
</style>

<div class="ef-wrap">

    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">Пошук</h3>
            <a href="<?= Url::to(['create']) ?>" class="ef-btn ef-btn-success">+ Нова категорія</a>
        </div>
        <form method="get" action="" class="ef-search-row" id="searchForm">
            <div class="ef-search-field">
                <label>Назва</label>
                <input type="text" name="CategorySearch[title]" value="<?= Html::encode($searchModel->title) ?>" placeholder="Назва…">
            </div>
            <div class="ef-search-field">
                <label>Статус</label>
                <?= Html::dropDownList('CategorySearch[status]', $searchModel->status,
                    [1 => 'Активна', 0 => 'Прихована'],
                    ['prompt' => 'Усі', 'style' => 'height:30px;padding:2px 8px;font-size:12px;border:1px solid #d1d5db;border-radius:4px;']) ?>
            </div>
            <button type="submit" class="ef-btn ef-btn-primary">Знайти</button>
            <a href="<?= Url::to(['index']) ?>" class="ef-btn ef-btn-outline">Скинути</a>
        </form>
    </div>

    <div class="ef-section">
        <div class="ef-section-head">
            <h3 class="ef-section-title">
                Категорії <span style="color:#94a3b8;font-weight:400;">(<?= count($records) ?>)</span>
            </h3>
        </div>
        <div style="overflow-x:auto;">
            <table class="ef-table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width:40px;">ID</th>
                        <th style="min-width:200px;">Назва</th>
                        <th style="width:120px;">Статус</th>
                        <th style="width:90px;">Дії</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $record): ?>
                    <tr data-id="<?= $record->id ?>" data-changed="0">
                        <td class="ef-id"><?= $record->id ?></td>
                        <td><input class="form-control" name="Category[<?= $record->id ?>][title]"
                                   value="<?= Html::encode($record->title) ?>"></td>
                        <td><?= Html::dropDownList(
                            "Category[{$record->id}][status]",
                            $record->status,
                            [1 => 'Активна', 0 => 'Прихована'],
                            ['class' => 'form-control']
                        ) ?></td>
                        <td style="text-align:center"><div style="display:inline-flex;gap:4px;flex-wrap:nowrap">
                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 576 512" fill="currentColor"><path d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path></svg>', ['view', 'id' => $record->id], ['class' => 'inv-act inv-act-view', 'title' => 'Переглянути', 'data-pjax' => '0']) ?>
                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 512 512" fill="currentColor"><path d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path></svg>', ['update', 'id' => $record->id], ['class' => 'inv-act inv-act-edit', 'title' => 'Редагувати', 'data-pjax' => '0']) ?>
                            <?= Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 448 512" fill="currentColor"><path d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg>', ['delete', 'id' => $record->id], ['class' => 'inv-act inv-act-del', 'title' => 'Видалити', 'data-pjax' => '0', 'data' => ['confirm' => 'Ви впевнені, що хочете видалити цей елемент?', 'method' => 'post']]) ?>
                        </div></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="ef-save-bar">
        <button class="ef-btn ef-btn-primary" id="btnSave" disabled>
            Зберегти змінені <span class="ef-badge" id="changedCount" style="display:none">0</span>
        </button>
        <span id="saveMsg" style="font-size:12px;color:#16a34a;display:none;font-weight:500;"></span>
    </div>

</div>

<?php
$csrf      = Yii::$app->request->csrfToken;
$csrfParam = Yii::$app->request->csrfParam;
$this->registerJs(<<<JS
    var changedRows = {};

    \$(document).on('input change', '#itemsTable tbody tr input, #itemsTable tbody tr select', function() {
        var row = \$(this).closest('tr');
        row.attr('data-changed', '1').addClass('ef-changed');
        changedRows[row.data('id')] = true;
        updateSaveBtn();
    });

    function updateSaveBtn() {
        var count = Object.keys(changedRows).length;
        if (count > 0) {
            \$('#btnSave').prop('disabled', false);
            \$('#changedCount').text(count).show();
        } else {
            \$('#btnSave').prop('disabled', true);
            \$('#changedCount').hide();
        }
    }

    \$('#btnSave').on('click', function() {
        var data = {};
        \$('#itemsTable tbody tr[data-changed="1"]').each(function() {
            var id = \$(this).data('id');
            data[id] = {};
            \$(this).find('input, select').each(function() {
                var match = \$(this).attr('name').match(/\[(\w+)\]$/);
                if (match) data[id][match[1]] = \$(this).val();
            });
        });
        var postData = { Category: data };
        postData['{$csrfParam}'] = '{$csrf}';
        \$.ajax({
            url: '/category/bulk-update',
            method: 'POST',
            data: postData,
            dataType: 'json',
            success: function(resp) {
                if (resp.saved > 0) {
                    \$('#itemsTable tbody tr[data-changed="1"]')
                        .attr('data-changed', '0').removeClass('ef-changed').addClass('ef-saved');
                    setTimeout(function() { \$('#itemsTable .ef-saved').removeClass('ef-saved'); }, 2000);
                    changedRows = {};
                    updateSaveBtn();
                    \$('#saveMsg').text('Збережено: ' + resp.saved + ' записів').show();
                    setTimeout(function() { \$('#saveMsg').fadeOut(); }, 3000);
                }
                if (resp.errors && Object.keys(resp.errors).length > 0) {
                    alert('Помилки: ' + JSON.stringify(resp.errors));
                }
            }
        });
    });

    \$('#searchForm').on('submit', function() {
        if (Object.keys(changedRows).length > 0) return confirm('Є незбережені зміни. Продовжити?');
    });
JS
); ?>
