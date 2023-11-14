<div class="container">
    <div class="row p-4">
        <div class="col-md-12">
            <table class="table table-hover table-bordered">
                <?php $i = 1; ?>
                <?php $switch = FALSE; ?>
                <?php
                if (count($main) < count($second)) {
                    $tmp = $main;
                    $main = $second;
                    $second = $main;
                    $switch = TRUE;
                }
                ?>
                <tr class="text-center" style="background-color: rgba(0,0,0, .08)">
                    <td>&nbsp;</td>
                    <td>Main DB: <?= $tbl_name['main'] ?></td>
                    <td>Second DB: <?= $tbl_name['second'] ?></td>
                </tr>

                <?php for ($i = 0; $i < count($main); $i++): ?>
                    <?php $main_tbl = $main[$i] ?>
                    <?php $second_tbl = isset($second[$i]) && in_array($main[$i], $second) ? $main[$i] : '' ?>
                    <tr style="background-color: <?= $second_tbl ? 'transparent' : 'rgba(200,0,0, .1)' ?>">
                        <td class="text-center"><?= $i + 1 ?></td>

                        <?php if (!$switch): ?>
                            <td><a href="/db/<?= $main_tbl ?>"><?= $main_tbl ?></a></td>
                            <td>
                                <?php if ($second_tbl): ?>
                                    <a href="/db/<?= $second_tbl ?>"><?= $second_tbl ?></a>
                                <?php endif; ?>
                            </td>
                        <?php else: ?>
                            <td><a href="/db/<?= $second_tbl ?>"><?= $second_tbl ?></a></td>
                            <td><?php if ($main_tbl): ?>
                                    <a href="/db/<?= $main_tbl ?>"><?= $main_tbl ?></a>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endfor; ?>
            </table>
        </div>
    </div>
</div>