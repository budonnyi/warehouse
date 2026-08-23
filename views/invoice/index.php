<?php

use app\models\Invoice;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var app\models\InvoiceSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Документи';
$this->params['breadcrumbs'][] = $this->title;

$tsp = ' '; // thin space for number_format (regular space, safe in all PHP versions)

$this->registerCss('
/* ─── Reset & tokens ─── */
.inv-wrap *{box-sizing:border-box}
.inv-wrap{
    --c-ink:      #0f172a;
    --c-ink2:     #475569;
    --c-ink3:     #94a3b8;
    --c-border:   #e2e8f0;
    --c-surface:  #ffffff;
    --c-bg:       #f8fafc;
    --c-done-bg:  #f0fdf4; --c-done-brd:#86efac; --c-done-txt:#15803d;
    --c-wip-bg:   #fffbeb; --c-wip-brd: #fcd34d; --c-wip-txt: #92400e;
    --c-ship-bg:  #eff6ff; --c-ship-brd:#93c5fd; --c-ship-txt:#1d4ed8;
    --c-cancel-bg:#fff1f2; --c-cancel-brd:#fda4af;--c-cancel-txt:#be123c;
    --r:4px;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
}

/* ─── Card wrapper ─── */
.inv-card{
    background:var(--c-surface);
    border:1px solid var(--c-border);
    border-radius:8px;
    overflow:hidden;
    box-shadow:0 1px 3px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
}

/* ─── Toolbar ─── */
.inv-toolbar{
    display:flex;align-items:center;justify-content:space-between;
    padding:14px 20px;
    border-bottom:1px solid var(--c-border);
    background:var(--c-surface);
}
.inv-toolbar-title{
    font-size:15px;font-weight:700;color:var(--c-ink);margin:0;letter-spacing:-.01em;
}
.inv-btn-create{
    display:inline-flex;align-items:center;gap:7px;
    padding:8px 18px;
    background:#16a34a;color:#fff;
    font-size:13px;font-weight:600;
    border-radius:6px;border:none;
    text-decoration:none;
    box-shadow:0 1px 2px rgba(22,163,74,.25);
    transition:background .15s,box-shadow .15s;
}
.inv-btn-create:hover{background:#15803d;color:#fff;text-decoration:none;box-shadow:0 2px 6px rgba(22,163,74,.35);}
.inv-btn-create svg{flex-shrink:0;}

/* ─── Scroll wrapper (mobile) ─── */
.inv-table-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}
.inv-table-scroll::-webkit-scrollbar{height:4px;}
.inv-table-scroll::-webkit-scrollbar-track{background:#f1f5f9;}
.inv-table-scroll::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:2px;}

/* ─── Table shell ─── */
.inv-wrap .table{
    width:100%;border-collapse:collapse;margin:0;min-width:900px;
}

/* ─── Head ─── */
.inv-wrap .table thead tr th{
    background:#6c757d;
    color:#f8f9fa;
    font-size:10.5px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;
    padding:10px 12px;
    border:none;
    white-space:nowrap;
}
.inv-wrap .table thead tr th:first-child{border-radius:0;}
.inv-wrap .table thead tr th a{color:#f8f9fa;}
.inv-wrap .table thead tr th a:hover{color:#fff;}

/* ─── Filter row ─── */
.inv-wrap .table tbody tr.filters td{
    padding:6px 8px;
    background:#f1f5f9;
    border-bottom:2px solid var(--c-border);
}
.inv-wrap .table tbody tr.filters td input,
.inv-wrap .table tbody tr.filters td select{
    width:100%;font-size:12px;padding:5px 8px;
    border:1px solid #cbd5e1;border-radius:var(--r);
    background:#fff;color:var(--c-ink);outline:none;
    transition:border-color .15s,box-shadow .15s;
}
.inv-wrap .table tbody tr.filters td input:focus,
.inv-wrap .table tbody tr.filters td select:focus{
    border-color:#60a5fa;box-shadow:0 0 0 2px rgba(96,165,250,.2);
}

/* ─── Data rows ─── */
.inv-wrap .table tbody tr td{
    padding:10px 12px;
    font-size:13px;color:var(--c-ink);
    border-bottom:1px solid var(--c-border);
    vertical-align:middle;
}
.inv-wrap .table tbody tr:last-child td{border-bottom:none;}

/* row accent */
.inv-row-done   td{background:var(--c-done-bg);  border-left:none;}
.inv-row-wip    td{background:var(--c-wip-bg);   border-left:none;}
.inv-row-ship   td{background:var(--c-ship-bg);  border-left:none;}
.inv-row-cancel td{background:var(--c-cancel-bg);border-left:none;}

.inv-row-done   td:first-child{border-left:3px solid var(--c-done-brd);}
.inv-row-wip    td:first-child{border-left:3px solid var(--c-wip-brd);}
.inv-row-ship   td:first-child{border-left:3px solid var(--c-ship-brd);}
.inv-row-cancel td:first-child{border-left:3px solid var(--c-cancel-brd);}

.inv-wrap .table tbody tr:hover td{filter:brightness(.975);}

/* ─── Doc-ref cell ─── */
.inv-doc-ref{display:flex;flex-direction:column;gap:3px;min-width:90px;}
.inv-doc-date{font-size:11px;color:var(--c-ink3);}
.inv-doc-num{
    font-size:13px;font-weight:700;color:#2563eb;
    text-decoration:none;letter-spacing:-.01em;
}
.inv-doc-num:hover{color:#1d4ed8;text-decoration:underline;}

/* ─── Customer ─── */
.inv-customer{
    font-size:13px;font-weight:500;color:var(--c-ink);
    text-decoration:none;display:block;
    max-width:220px;
    word-break:break-word;
}
.inv-customer:hover{color:#2563eb;text-decoration:underline;}

/* ─── Product list ─── */
.inv-prods{list-style:none;margin:0;padding:0;min-width:200px;}
.inv-prods li{
    display:grid;grid-template-columns:1fr 30px 88px;gap:6px;
    align-items:baseline;
    padding:4px 0;
    border-bottom:1px solid rgba(0,0,0,.05);
    line-height:1.3;
}
.inv-prods li:last-child{border-bottom:none;}
.inv-pname{font-size:12.5px;color:var(--c-ink);}
.inv-pqty{
    font-size:11px;color:#fff;background:#94a3b8;
    border-radius:10px;padding:1px 5px;
    text-align:center;font-weight:600;
}
.inv-pprice{
    font-size:12px;font-weight:600;color:#374151;
    text-align:right;font-variant-numeric:tabular-nums;
}

/* ─── Payment block ─── */
.inv-pay{display:flex;flex-direction:column;gap:4px;min-width:120px;white-space:nowrap;}
.inv-pay-row{display:flex;justify-content:space-between;align-items:center;gap:8px;}
.inv-pay-lbl{font-size:11px;color:var(--c-ink3);white-space:nowrap;}
.inv-pay-in{font-size:13px;font-weight:700;color:#16a34a;font-variant-numeric:tabular-nums;}
.inv-pay-out{font-size:13px;font-weight:700;color:#dc2626;font-variant-numeric:tabular-nums;}
.inv-pay-diff-row{
    margin-top:2px;padding-top:4px;
    border-top:1px solid var(--c-border);
}
.inv-pay-diff{font-size:13px;font-weight:700;color:#2563eb;font-variant-numeric:tabular-nums;}

/* ─── Total ─── */
.inv-total{
    font-size:14px;font-weight:800;color:var(--c-ink);
    font-variant-numeric:tabular-nums;white-space:nowrap;
}

/* ─── Status badge ─── */
.inv-badge{
    display:inline-flex;align-items:center;justify-content:center;
    padding:3px 10px;border-radius:20px;
    font-size:11px;font-weight:700;letter-spacing:.03em;white-space:nowrap;
}
.inv-b-done  {background:var(--c-done-bg);  color:var(--c-done-txt);  border:1px solid var(--c-done-brd);}
.inv-b-wip   {background:var(--c-wip-bg);   color:var(--c-wip-txt);   border:1px solid var(--c-wip-brd);}
.inv-b-ship  {background:var(--c-ship-bg);  color:var(--c-ship-txt);  border:1px solid var(--c-ship-brd);}
.inv-b-cancel{background:var(--c-cancel-bg);color:var(--c-cancel-txt);border:1px solid var(--c-cancel-brd);}
.inv-b-def   {background:#f1f5f9;color:#475569;border:1px solid #cbd5e1;}

/* ─── Action buttons ─── */
.inv-actions{display:flex;gap:4px;align-items:center;justify-content:center;flex-wrap:nowrap;}
.inv-act{
    display:inline-flex;align-items:center;justify-content:center;
    width:30px;height:30px;border-radius:6px;
    border:1px solid transparent;
    background:transparent;color:#94a3b8;
    text-decoration:none;
    transition:all .15s;
}
.inv-act:hover{text-decoration:none;}
.inv-act-view:hover  {background:#eff6ff;color:#2563eb;border-color:#bfdbfe;}
.inv-act-edit:hover  {background:#f0fdf4;color:#16a34a;border-color:#bbf7d0;}
.inv-act-del:hover   {background:#fff1f2;color:#e11d48;border-color:#fecdd3;}

/* ─── Summary & pagination ─── */
.inv-wrap .summary{margin-left:8px;margin-top:5px;display:block;}
.inv-wrap .pagination{margin-top:5px;}
');
?>

<div class="row">
    <section class="col-lg-12 inv-wrap">

        <div class="inv-card">

            <div class="inv-toolbar">
                <?php
                $documentTypes = [
                    'bill'     => 'Новий рахунок',
                    'sale'     => 'Нова накладна',
                    'order'    => 'Додати до замовлення',
                    'import'   => 'Новий інвойс',
                    'income'   => 'Нова закупка',
                    'office'   => 'Офісні',
                    'rejected' => 'Відмінені',
                ];
                ?>
                <h2 class="inv-toolbar-title"><?= Html::encode($this->title) ?></h2>
                <?= Html::a(
                    '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 448 512" fill="currentColor"><path d="M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"/></svg>'
                    . Html::encode($documentTypes[$action] ?? 'Створити'),
                    ['create', 'action' => $action],
                    ['class' => 'inv-btn-create']
                ) ?>
            </div>

            <?php Pjax::begin(); ?>

            <div class="inv-table-scroll">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'tableOptions' => ['class' => 'table'],
                'rowOptions'   => function ($model) {
                    if ($model->status == 1) {
                        return ['class' => 'inv-row-done'];
                    } elseif (in_array($model->status, [2, 6, 7, 9])) {
                        return ['class' => 'inv-row-wip'];
                    } elseif (in_array($model->status, [3, 4, 5])) {
                        return ['class' => 'inv-row-ship'];
                    } elseif ($model->status == 0) {
                        return ['class' => 'inv-row-cancel'];
                    }
                    return [];
                },
                'columns' => [

                    /* checkbox */
                    [
                        'class'          => 'yii\grid\CheckboxColumn',
                        'headerOptions'  => ['style' => 'width:36px;text-align:center'],
                        'checkboxOptions' => function ($model) {
                            return ['value' => $model->id];
                        },
                    ],

                    /* ── Order (import) ── */
                    [
                        'attribute'     => 'order_num',
                        'visible'       => $action == 'import',
                        'label'         => 'Order',
                        'headerOptions' => ['style' => 'width:100px'],
                        'value'         => function ($data) {
                            $date = !empty($data->order_date) ? date('d.m.Y', strtotime($data->order_date)) : '—';
                            return '<div class="inv-doc-ref">'
                                . '<span class="inv-doc-date">' . $date . '</span>'
                                . '<a class="inv-doc-num" href="' . Url::to(['view', 'id' => $data->id]) . '">' . Html::encode($data->order_num) . '</a>'
                                . '</div>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Рахунок ── */
                    [
                        'attribute'     => 'bill',
                        'label'         => 'Рахунок',
                        'headerOptions' => ['style' => 'width:105px'],
                        'visible'       => $action != 'import',
                        'value'         => function ($data) {
                            $date = !empty($data->bill_date) ? date('d.m.Y', strtotime($data->bill_date)) : '—';
                            return '<div class="inv-doc-ref">'
                                . '<span class="inv-doc-date">' . $date . '</span>'
                                . '<a class="inv-doc-num" href="' . Url::to(['view', 'id' => $data->id]) . '">' . Html::encode($data->bill) . '</a>'
                                . '</div>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Накладна ── */
                    [
                        'attribute'     => 'invoice',
                        'label'         => 'Накладна',
                        'visible'       => $action != 'import',
                        'headerOptions' => ['style' => 'width:105px'],
                        'value'         => function ($data) {
                            $date = !empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '—';
                            return '<div class="inv-doc-ref">'
                                . '<span class="inv-doc-date">' . $date . '</span>'
                                . '<a class="inv-doc-num" href="' . Url::to(['view', 'id' => $data->id]) . '">' . Html::encode($data->invoice) . '</a>'
                                . '</div>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Adv Invoice (import) ── */
                    [
                        'attribute'     => 'bill',
                        'label'         => 'Adv Invoice',
                        'headerOptions' => ['style' => 'width:105px'],
                        'visible'       => $action == 'import',
                        'value'         => function ($data) {
                            $date = !empty($data->bill_date) ? date('d.m.Y', strtotime($data->bill_date)) : '—';
                            return '<div class="inv-doc-ref">'
                                . '<span class="inv-doc-date">' . $date . '</span>'
                                . '<a class="inv-doc-num" href="' . Url::to(['view', 'id' => $data->id]) . '">' . Html::encode($data->bill) . '</a>'
                                . '</div>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Invoice (import) ── */
                    [
                        'attribute'     => 'invoice',
                        'visible'       => $action == 'import',
                        'label'         => 'Invoice',
                        'headerOptions' => ['style' => 'width:105px'],
                        'value'         => function ($data) {
                            $date = !empty($data->date) ? date('d.m.Y', strtotime($data->date)) : '—';
                            return '<div class="inv-doc-ref">'
                                . '<span class="inv-doc-date">' . $date . '</span>'
                                . '<a class="inv-doc-num" href="' . Url::to(['view', 'id' => $data->id]) . '">' . Html::encode($data->invoice) . '</a>'
                                . '</div>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Клієнт ── */
                    [
                        'attribute'     => 'customer_id',
                        'label'         => 'Клієнт',
                        'headerOptions' => ['style' => 'width:210px'],
                        'filter'        => \yii\helpers\ArrayHelper::map(
                            app\models\Customer::find()->orderBy(['name' => SORT_ASC])->all(),
                            'id', 'name'
                        ),
                        'visible'       => $action != 'import',
                        'value'         => function ($data) {
                            return '<a class="inv-customer" title="' . Html::encode($data->customers->name ?? '') . '" href="' . Url::to(['customer/view', 'id' => $data->customer_id]) . '">'
                                . Html::encode($data->customers->name ?? '—')
                                . '</a>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Продукція ── */
                    [
                        'label'         => 'Продукція',
                        'headerOptions' => ['style' => 'min-width:340px'],
                        'value'         => function ($data) {
                            $skip = ['EMB Packing', 'Ex-1 document fee'];
                            $rows = '';
                            foreach ($data->items as $item) {
                                if (in_array($item->products->name ?? '', $skip)) {
                                    continue;
                                }
                                $rows .= '<li>'
                                    . '<span class="inv-pname">' . Html::encode($item->products->name ?? '—') . '</span>'
                                    . '<span class="inv-pqty">'  . (int)$item->quantity . '</span>'
                                    . '<span class="inv-pprice">' . number_format((float)$item->price, 2, '.', ' ') . '</span>'
                                    . '</li>';
                            }
                            return $rows
                                ? '<ul class="inv-prods">' . $rows . '</ul>'
                                : '<span style="color:#94a3b8;font-size:12px">—</span>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Оплата ── */
                    [
                        'label'         => 'Оплата',
                        'headerOptions' => ['style' => 'width:145px'],
                        'visible'       => $action != 'import',
                        'value'         => function ($data) {
                            $income  = 0;
                            $expense = 0;
                            foreach ($data->payments as $item) {
                                if ($item->direction === 'income') {
                                    $income  += $item->amount;
                                } else {
                                    $expense += $item->amount;
                                }
                            }
                            if ($income == 0 && $expense == 0) {
                                return '<span style="color:#94a3b8;font-size:12px">—</span>';
                            }
                            $html = '<div class="inv-pay">';
                            if ($income > 0) {
                                $html .= '<div class="inv-pay-row">'
                                    . '<span class="inv-pay-lbl">Отримано</span>'
                                    . '<span class="inv-pay-in">+' . number_format($income, 2, '.', ' ') . '</span>'
                                    . '</div>';
                            }
                            if ($expense > 0) {
                                $html .= '<div class="inv-pay-row">'
                                    . '<span class="inv-pay-lbl">Сплачено</span>'
                                    . '<span class="inv-pay-out">−' . number_format($expense, 2, '.', ' ') . '</span>'
                                    . '</div>';
                            }
                            if ($income > 0 && $expense > 0) {
                                $html .= '<div class="inv-pay-row inv-pay-diff-row">'
                                    . '<span class="inv-pay-lbl">Різниця</span>'
                                    . '<span class="inv-pay-diff">' . number_format($income - $expense, 2, '.', ' ') . '</span>'
                                    . '</div>';
                            }
                            $html .= '</div>';
                            return $html;
                        },
                        'format' => 'html',
                    ],

                    /* ── Сума ── */
                    [
                        'attribute'      => 'total_amount',
                        'label'          => 'Сума',
                        'visible'        => $action != 'import',
                        'headerOptions'  => ['style' => 'width:105px;text-align:right'],
                        'contentOptions' => ['style' => 'text-align:right'],
                        'value'          => function ($data) {
                            return '<span class="inv-total">' . number_format((float)$data->total_amount, 2, '.', ' ') . '</span>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Сума SEK (import) ── */
                    [
                        'attribute'      => 'total_amount_sek',
                        'headerOptions'  => ['style' => 'width:105px;text-align:right'],
                        'contentOptions' => ['style' => 'text-align:right'],
                        'visible'        => $action == 'import',
                    ],

                    /* ── Статус ── */
                    [
                        'attribute'      => 'status',
                        'label'          => 'Статус',
                        'headerOptions'  => ['style' => 'width:110px;text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'filter'         => Yii::$app->params['statuses'],
                        'value'          => function ($data) {
                            $statuses = Yii::$app->params['statuses'];
                            $label    = isset($statuses[$data->status]) ? $statuses[$data->status] : '—';
                            if ($data->status == 1) {
                                $cls = 'inv-b-done';
                            } elseif (in_array($data->status, [2, 6, 7, 9])) {
                                $cls = 'inv-b-wip';
                            } elseif (in_array($data->status, [3, 4, 5])) {
                                $cls = 'inv-b-ship';
                            } elseif ($data->status == 0) {
                                $cls = 'inv-b-cancel';
                            } else {
                                $cls = 'inv-b-def';
                            }
                            return '<span class="inv-badge ' . $cls . '">' . Html::encode($label) . '</span>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Файли (import) ── */
                    [
                        'label'          => 'Файли',
                        'visible'        => $action == 'import',
                        'headerOptions'  => ['style' => 'width:60px;text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'value'          => function ($data) {
                            $cnt = !empty($data->attachments) ? count($data->attachments) : 0;
                            return $cnt > 0
                                ? '<span class="inv-badge inv-b-ship">' . $cnt . '</span>'
                                : '<span style="color:#94a3b8">—</span>';
                        },
                        'format' => 'html',
                    ],

                    /* ── Дії ── */
                    [
                        'class'          => ActionColumn::className(),
                        'headerOptions'  => ['style' => 'width:96px;text-align:center'],
                        'contentOptions' => ['style' => 'text-align:center'],
                        'urlCreator'     => function ($action, Invoice $model, $key, $index, $column) {
                            return Url::toRoute([$action, 'id' => $model->id]);
                        },
                        'template'  => '<div style="display:inline-flex;gap:4px;flex-wrap:nowrap">{view} {update} {delete}</div>',
                        'buttons'   => [
                            'view' => function ($url) {
                                return Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 576 512" fill="currentColor"><path d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"/></svg>',
                                    $url,
                                    ['class' => 'inv-act inv-act-view', 'title' => 'Переглянути', 'data-pjax' => '0']
                                );
                            },
                            'update' => function ($url) {
                                return Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 512 512" fill="currentColor"><path d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"/></svg>',
                                    $url,
                                    ['class' => 'inv-act inv-act-edit', 'title' => 'Редагувати', 'data-pjax' => '0']
                                );
                            },
                            'delete' => function ($url) {
                                return Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 448 512" fill="currentColor"><path d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"/></svg>',
                                    $url,
                                    ['class' => 'inv-act inv-act-del', 'title' => 'Видалити', 'data-pjax' => '0', 'data-confirm' => 'Ви впевнені, що хочете видалити цей елемент?', 'data-method' => 'post']
                                );
                            },
                        ],
                    ],

                ],
            ]); ?>

            </div><!-- /.inv-table-scroll -->

            <?php Pjax::end(); ?>

        </div>

    </section>
</div>
