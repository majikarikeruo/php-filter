<header class="header">
    <h1><a href="<?= $_ENV['APP_URL']; ?>">アンケートアプリ</a></h1>
    <div class="header-sub">
        <div class="navbar-header user-name">
            <p><?= $_SESSION['user_name'] ?>さん</p>
        </div>
        <div>
            <a href="../view/create.php">アンケート新規登録</a>
        </div>
        <form class="logout-form" action="../controller/logout.php" method="post" onsubmit="return confirm('本当にログアウトしますか？');">
            <button type="submit" class="logout-button">ログアウト</button>
        </form>
    </div>
</header>