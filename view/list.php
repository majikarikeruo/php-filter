<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

session_start();
loginCheck();

// データベース接続
$pdo = db_conn();

// アンケートデータの取得
$stmt = $pdo->prepare("
    SELECT 
        surveys.id AS survey_id, 
        surveys.content AS survey_content, 
        surveys.created_at AS survey_created_at, 
        users.user_name AS creator_name
    FROM 
        surveys AS surveys
    JOIN 
        users AS users ON surveys.user_id = users.id 
    ORDER BY 
        surveys.created_at DESC
");
$stmt->execute();
$surveys = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アンケート一覧 - アンケートアプリ</title>
    <link href="../assets/css/style.css" rel="stylesheet" />

</head>

<body>
    <?php include __DIR__ . '/../view/components/header.php'; ?>

    <div class="survey-container">
        <h1>アンケート一覧</h1>

        <!-- 検索フォームを追加 -->
        <div class="search-form">
            <input type="text" id="searchInput" class="form-field" placeholder="キーワードを入力して検索">
        </div>
        <div id="surveyList">
            <?php foreach ($surveys as $survey) : ?>
                <div class="card">
                    <div class="card-content">
                        <p><?php echo htmlspecialchars(substr($survey['survey_content'], 0, 100)) . '...'; ?>
                            @<?= htmlspecialchars($survey['creator_name']); ?>さん

                            <small><?php echo date('Y年m月d日 H:i', strtotime($survey['survey_created_at'])); ?></small>
                        </p>
                    </div>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="detail.php?id=<?php echo $survey['survey_id']; ?>">詳細</a>

                        <form action="../controller/survey_delete.php" method="POST">
                            <input type="hidden" name="id" value="<?php echo $survey['survey_id']; ?>">
                            <input type="submit" value="削除" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($surveys)) : ?>
                <p>アンケートがまだありません。</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include __DIR__ . '/../view/components/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // PHP のデータを JavaScript の配列に変換
        const surveys = <?php echo json_encode($surveys); ?>;

        // アンケートを表示する関数
        function renderSurveys(surveysToRender) {
            const $surveyList = $('#surveyList');
            $surveyList.empty();

            $.each(surveysToRender, function(index, survey) {
                const $card = $('<div>').addClass('card');
                $card.html(`
                    <div class="card-content">
                        <p>${survey.survey_content.substr(0, 100)}...
                            @${survey.creator_name}さん
                            <small>${new Date(survey.survey_created_at).toLocaleString('ja-JP')}</small>
                        </p>
                    </div>
                    <div class="card-actions">
                        <a class="btn btn-primary" href="detail.php?id=${survey.survey_id}">詳細</a>
                        <form action="../controller/survey_delete.php" method="POST">
                            <input type="hidden" name="id" value="${survey.survey_id}">
                            <input type="submit" value="削除" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">
                        </form>
                    </div>
                `);
                $surveyList.append($card);
            });

            if (surveysToRender.length === 0) {
                $surveyList.html('<p>検索結果がありません。</p>');
            }
        }

        // 初期表示
        renderSurveys(surveys);

        // 検索機能
        $('#searchInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            const filteredSurveys = $.grep(surveys, function(survey) {
                return survey.survey_content.toLowerCase().includes(searchTerm) ||
                    survey.creator_name.toLowerCase().includes(searchTerm);
            });
            console.log(filteredSurveys)
            renderSurveys(filteredSurveys);
        });
    </script>
</body>

</html>