<div class="container-fluid p-5">
    <div class="row">
        <?php foreach ($diff as $tbl_name => $vals): ?>
            <table class="table table-hover table-bordered">
                <tr style="background-color: <?= 'transparent' //$vals['has_diff'] == TRUE ? 'rgba(150,0,0, .1)' : 'rgba(0,150,0, .1)'  ?>">
                    <td colspan="20"><a href="/db/<?= $tbl_name ?>"><?= $tbl_name ?></a></td>
                </tr>
                <?php foreach ($vals['diff'] as $opt): ?>
                    <tr>
                        <?php foreach ($opt as $o): ?>
                            <td style="background-color: <?= !$o['eq'] ? 'rgba(200,0,0, .8)' : 'transparent' ?>">
                                <table class="table table-bordered">
                                    <tr>
                                        <td style="font-size: 8px; background-color: rgba(0,0,0, .08)">
                                            <?= $o['attr'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $o['m'] ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <?= $o['s'] ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>

                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endforeach; ?>
    </div>
</div>
