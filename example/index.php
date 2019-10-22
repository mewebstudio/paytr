<?php
require __DIR__ . '/view/header.php';
?>

<div class="container form-container">
    <div class="card">
        <div class="card-header">PayTR ödeme formu</div>
        <div class="card-body">
            <form method="post" action="payment.php" role="form">
                <div class="form-group">
                    <label for="name">İsim</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="İsim" required>
                </div>
                <div class="form-group">
                    <label for="phone">Telefon</label>
                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Telefon" required>
                </div>
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="E-posta" required>
                </div>
                <div class="form-group">
                    <label for="email">Tutar</label>
                    <div class="input-group">
                        <input type="text" name="amount" id="amount" class="form-control" placeholder="Tutar" required>
                        <div class="input-group-append">
                            <span class="input-group-text">₺</span>
                        </div>
                    </div>
                    <span class="form-text text-muted">Örnek: 10, 20.3, 100.24</span>
                </div>
                <hr>
                <div class="form-group mb-0">
                    <button type="submit" class="btn btn-block btn-success">GÖNDER</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
require __DIR__ . '/view/footer.php';
?>
