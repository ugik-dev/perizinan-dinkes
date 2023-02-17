<?php
// $resScrore = explode(',', $contentData['score_arr']);
// echo json_encode($contentData);
?>
<div class="container">

    <div class="wrapperwrapper-content animated fadeInRight">
        <div class="ibox section-container">
            <div class="ibox-content">
                <div class="col-lg-12">
                    <h1>Hasil : <?= status_ujian($contentData['hasil']) ?></h1>
                    <h1>Akumulasi Score : <?= $contentData['score'] ?></h1>
                    <hr>
                    <h2>Benar : <?= $contentData['benar'] ?></h2>
                    <h2>Salah : <?= $contentData['salah'] ?></h2>
                    <h2>Tidak diisi : <?= $contentData['kosong'] ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>