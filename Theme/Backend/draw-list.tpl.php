<?php
/**
 * Orange Management
 *
 * PHP Version 7.1
 *
 * @category   TBD
 * @package    TBD
 * @author     OMS Development Team <dev@oms.com>
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://orange-management.com
 */
/**
 * @var \phpOMS\Views\View $this
 */

$footerView = new \Web\Views\Lists\PaginationView($this->app, $this->request, $this->response);
$footerView->setTemplate('/Web/Templates/Lists/Footer/PaginationBig');
$footerView->setPages(20);
$footerView->setPage(1);

$images = $this->getData('images');

echo $this->getData('nav')->render(); ?>
<div class="row">
    <div class="col-xs-12">
        <div class="box wf-100">
            <table class="table red">
                <caption><?= $this->getHtml('Images') ?></caption>
                <thead>
                <tr>
                    <td class="wf-100"><?= $this->getHtml('Name') ?>
                    <td><?= $this->getHtml('Creator') ?>
                    <td><?= $this->getHtml('Created') ?>
                <tfoot>
                <tr>
                    <td colspan="3"><?= htmlspecialchars($footerView->render(), ENT_COMPAT, 'utf-8'); ?>
                <tbody>
                <?php $count = 0; foreach($images as $key => $value) : $count++;
                $url = \phpOMS\Uri\UriFactory::build('{/base}/{/lang}/backend/draw/single?{?}&id=' . $value->getId()); ?>
                    <tr>
                        <td><a href="<?= $url; ?>"><?= htmlspecialchars($value->getMedia()->getName(), ENT_COMPAT, 'utf-8'); ?></a>
                        <td><a href="<?= $url; ?>"><?= htmlspecialchars($value->getMedia()->getCreatedBy(), ENT_COMPAT, 'utf-8'); ?></a>
                        <td><a href="<?= $url; ?>"><?= htmlspecialchars($value->getMedia()->getCreatedAt()->format('Y-m-d'), ENT_COMPAT, 'utf-8'); ?></a>
                <?php endforeach; ?>
                <?php if($count === 0) : ?>
                <tr><td colspan="5" class="empty"><?= $this->getHtml('Empty', 0, 0); ?>
                        <?php endif; ?>
            </table>
        </div>
    </div>
</div>
