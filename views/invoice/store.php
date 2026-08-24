<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */

$this->title = 'Склад';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
/* ─── Reset & tokens ─── */
.store-wrap *{box-sizing:border-box}
.store-wrap{
    --c-ink:      #0f172a;
    --c-ink2:     #475569;
    --c-ink3:     #94a3b8;
    --c-border:   #e2e8f0;
    --c-surface:  #ffffff;
    --c-bg:       #f8fafc;
    --r:4px;
    font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;
}

/* ─── Card wrapper ─── */
.store-card{
    background:var(--c-surface);
    border:1px solid var(--c-border);
    border-radius:8px;
    overflow:hidden;
    box-shadow:0 1px 3px rgba(15,23,42,.06),0 1px 2px rgba(15,23,42,.04);
}

/* ─── Toolbar ─── */
.store-toolbar{
    display:flex;align-items:center;
    padding:14px 20px;
    border-bottom:1px solid var(--c-border);
    background:var(--c-surface);
}
.store-toolbar-title{
    font-size:15px;font-weight:700;color:var(--c-ink);margin:0;letter-spacing:-.01em;
}

/* ─── Scroll wrapper (mobile) ─── */
.store-scroll{overflow-x:auto;-webkit-overflow-scrolling:touch;}
.store-scroll::-webkit-scrollbar{height:4px;}
.store-scroll::-webkit-scrollbar-track{background:#f1f5f9;}
.store-scroll::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:2px;}

/* ─── Table ─── */
.store-wrap .store-table{
    width:100%;border-collapse:collapse;margin:0;min-width:800px;
}

/* ─── Head ─── */
.store-wrap .store-table thead tr th{
    background:#6c757d;
    color:#f8f9fa;
    font-size:10.5px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;
    padding:10px 12px;
    border:none;
    white-space:nowrap;
}

/* ─── Data rows ─── */
.store-wrap .store-table tbody tr td{
    padding:10px 12px;
    font-size:13px;color:var(--c-ink);
    border-bottom:1px solid var(--c-border);
    vertical-align:middle;
}
.store-wrap .store-table tbody tr:last-child td{border-bottom:none;}
.store-wrap .store-table tbody tr:hover td{filter:brightness(.975);}

/* ─── Product link ─── */
.store-prod-link{
    font-size:13px;font-weight:500;color:var(--c-ink);
    text-decoration:none;
}
.store-prod-link:hover{color:#2563eb;text-decoration:underline;}

/* ─── ID cell ─── */
.store-id{font-size:11px;color:var(--c-ink3);font-variant-numeric:tabular-nums;}

/* ─── Numeric cells ─── */
.store-num{
    font-size:13px;font-weight:600;font-variant-numeric:tabular-nums;
    text-align:right;
}
.store-num-qty{color:#1d4ed8;}
.store-num-order{color:#92400e;}
.store-num-pay{color:#0369a1;}
.store-num-bill{color:#475569;}
.store-num-sold{color:#dc2626;}
.store-num-income{color:#16a34a;}
.store-num-profit{color:#7c3aed;}

/* ─── Qty badge ─── */
.store-badge{
    display:inline-flex;align-items:center;justify-content:center;
    min-width:32px;padding:2px 8px;border-radius:20px;
    font-size:11px;font-weight:700;
}
.store-badge-stock {background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;}
.store-badge-order {background:#fffbeb;color:#92400e;border:1px solid #fcd34d;}
.store-badge-zero  {background:#f1f5f9;color:#94a3b8;border:1px solid #e2e8f0;}
.store-badge-neg   {background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}

/* ─── Money amount ─── */
.store-money{
    font-size:13px;font-weight:700;
    font-variant-numeric:tabular-nums;
    white-space:nowrap;
}
.store-money-sold{color:#dc2626;}
.store-money-income{color:#16a34a;}
.store-money-profit-pos{color:#7c3aed;}
.store-money-profit-neg{color:#dc2626;}
');
?>

<div class="row">
    <section class="col-lg-12 store-wrap">

        <div class="store-card">

            <div class="store-toolbar">
                <h2 class="store-toolbar-title"><?= Html::encode($this->title) ?></h2>
            </div>

            <div class="store-scroll">
                <table class="store-table">
                    <thead>
                        <tr>
                            <th style="width:50px">ID</th>
                            <th><?= Yii::t('app', 'Товар') ?></th>
                            <th style="width:100px;text-align:center"><?= Yii::t('app', 'На складі') ?></th>
                            <th style="width:100px;text-align:center"><?= Yii::t('app', 'Замовлено') ?></th>
                            <th style="width:100px;text-align:right"><?= Yii::t('app', 'Оплачено') ?></th>
                            <th style="width:90px;text-align:center"><?= Yii::t('app', 'Рахунки') ?></th>
                            <th style="width:100px;text-align:right"><?= Yii::t('app', 'Продали') ?></th>
                            <th style="width:100px;text-align:right"><?= Yii::t('app', 'Купили') ?></th>
                            <th style="width:110px;text-align:right"><?= Yii::t('app', 'Прибуток') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($storeItems)) { ?>
                        <?php foreach ($storeItems as $productId => $storeItem) { ?>
                            <?php if (!empty($storeItem['onStoreQuantity']) || !empty($storeItem['orderedQuantity'])) { ?>
                                <?php
                                    $profit = $storeItem['profit'] ?? 0;
                                    $profitClass = $profit >= 0 ? 'store-money-profit-pos' : 'store-money-profit-neg';
                                    $stock = (int)($storeItem['onStoreQuantity'] ?? 0);
                                    $ordered = (int)($storeItem['orderedQuantity'] ?? 0);
                                ?>
                                <tr>
                                    <td><span class="store-id"><?= $productId ?></span></td>
                                    <td>
                                        <a class="store-prod-link" href="<?= Url::toRoute(['product/view', 'id' => $productId]) ?>">
                                            <?= Html::encode($storeItem['product_name'] ?? '') ?>
                                        </a>
                                    </td>
                                    <td style="text-align:center">
                                        <?php if ($stock > 0): ?>
                                            <span class="store-badge store-badge-stock"><?= $stock ?></span>
                                        <?php elseif ($stock === 0): ?>
                                            <span class="store-badge store-badge-zero">0</span>
                                        <?php else: ?>
                                            <span class="store-badge store-badge-neg"><?= $stock ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php if ($ordered > 0): ?>
                                            <span class="store-badge store-badge-order"><?= $ordered ?></span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:right">
                                        <?php $payed = $storeItem['payed'] ?? 0; ?>
                                        <?php if ($payed): ?>
                                            <span class="store-money" style="color:#0369a1"><?= number_format((float)$payed, 2, '.', ' ') ?></span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:center">
                                        <?php $billed = $storeItem['billedQuantity'] ?? 0; ?>
                                        <?php if ($billed): ?>
                                            <span class="store-badge store-badge-zero" style="color:#475569;border-color:#cbd5e1"><?= (int)$billed ?></span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:right">
                                        <?php $sold = $storeItem['sold'] ?? 0; ?>
                                        <?php if ((float)$sold < 0): ?>
                                            <span class="store-badge store-badge-neg"><?= number_format((float)$sold, 2, '.', ' ') ?></span>
                                        <?php elseif ($sold): ?>
                                            <span class="store-money store-money-sold"><?= number_format((float)$sold, 2, '.', ' ') ?></span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:right">
                                        <?php $income = $storeItem['income'] ?? 0; ?>
                                        <?php if ((float)$income < 0): ?>
                                            <span class="store-badge store-badge-neg"><?= number_format((float)$income, 2, '.', ' ') ?></span>
                                        <?php elseif ($income): ?>
                                            <span class="store-money store-money-income"><?= number_format((float)$income, 2, '.', ' ') ?></span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:right">
                                        <?php if ($profit): ?>
                                            <span class="store-money <?= $profitClass ?>"><?= number_format((float)$profit, 2, '.', ' ') ?></span>
                                        <?php else: ?>
                                            <span style="color:#94a3b8">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>

    </section>
</div>