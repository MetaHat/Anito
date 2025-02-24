<?php
require 'tohost.php';

// Get animeId from the URL
if (!isset($_GET['animeId']) || empty($_GET['animeId'])) {
    die("Anime ID is required.");
}

$animeId = htmlspecialchars($_GET['animeId']);

// Endpoint for anime info
$endpoint = '/api/v2/hianime/anime/' . $animeId;
$apiUrl = BASE_API_URL . $endpoint;

// Fetch data from API
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if (!$data || !$data['success']) {
    die("Failed to fetch anime information.");
}

$anime = $data['data']['anime']['info'];
$moreInfo = $data['data']['anime']['moreInfo'];
$recommendedAnimes = $data['data']['recommendedAnimes'];
$relatedAnimes = $data['data']['relatedAnimes'];
$seasons = $data['data']['seasons'];
$promotionalVideos = $anime['promotionalVideos'];
$characterVoiceActors = $anime['characterVoiceActor'];
?>
<html>
    <head>
        <style>
            /* 🌟 Main Container */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* 🌟 Anime Details */
.anime-details {
    text-align: center;
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.anime-details h1 {
    font-size: 28px;
    color: #222;
    margin-bottom: 10px;
}

.anime-details img {
    max-width: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* 🌟 More Info */
.more-info {
    background: #fff;
    padding: 20px;
    margin-top: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.more-info h2 {
    text-align: center;
    font-size: 24px;
    color: #333;
}

.more-info p {
    font-size: 16px;
    color: #555;
    margin-bottom: 8px;
}

/* 🌟 Anime Grid */
.anime-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
    margin-top: 10px;
}

/* 🌟 Anime Cards */
.anime-card {
    background: #ffcc80;
    text-decoration: none;
    color: #222;
    padding: 10px;
    border-radius: 8px;
    transition: 0.3s;
    width: 180px;
    text-align: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.anime-card:hover {
    background: #ffb74d;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.anime-card img {
    width: 100%;
    border-radius: 8px;
}

.anime-card h3 {
    font-size: 16px;
    margin-top: 8px;
}

/* 🌟 Related Anime Section */
.related-anime-nav {
    background: #fff;
    padding: 20px;
    margin-top: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.related-anime-nav h2 {
    text-align: center;
    font-size: 24px;
    color: #333;
}

.related-list {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

.related-item {
    width: 180px;
    text-align: center;
}

.related-poster img {
    width: 100%;
    border-radius: 8px;
}

.anime-tags {
    margin-top: 5px;
}

.anime-tags .tag {
    display: inline-block;
    background: #ff6600;
    color: white;
    padding: 3px 6px;
    border-radius: 4px;
    font-size: 12px;
}

        </style>
    </head>
<section class="section anime-details">
    <h1><?= htmlspecialchars($anime['name']) ?></h1>
    <img src="<?= $anime['poster'] ?>" alt="<?= htmlspecialchars($anime['name']) ?>">
    <!--<p><?= htmlspecialchars($anime['description']) ?></p>-->
</section>

<section class="section more-info">
    <h2>Details</h2>
    <ul>
        <p><strong>Type:</strong> <?= $anime['stats']['type'] ?></p>
            <p><strong>Rating:</strong> <?= $anime['stats']['rating'] ?></p>
            <p><strong>Quality:</strong> <?= $anime['stats']['quality'] ?></p>
            <p><strong>Duration:</strong> <?= $anime['stats']['duration'] ?></p>
            <p><strong>Aired: </strong><?= htmlspecialchars($moreInfo['aired']) ?></p>
            <p><strong>Genres:</strong></strong> <?= implode(', ', $moreInfo['genres']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($moreInfo['status']) ?></p>
            <p><strong>Studios:</strong> <?= htmlspecialchars($moreInfo['studios']) ?></p>
            <p><strong>Episodes:</strong> Sub: <?= $anime['stats']['episodes']['sub'] ?> | Dub: <?= $anime['stats']['episodes']['dub'] ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($anime['description']) ?></p>
    </ul>
</section>

<section class="section seasons">
    <h2>Seasons</h2>
    <div class="anime-grid">
        <?php foreach ($seasons as $season): ?>
            <a href="anime-info.php?animeId=<?= $season['id'] ?>" class="anime-card">
                <img src="<?= $season['poster'] ?>" alt="<?= htmlspecialchars($season['name']) ?>">
                <div class="card-info">
                    <h3><?= htmlspecialchars($season['name']) ?></h3>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

    <!-- Recommended Animes -->
    <section class="section recommended-animes">
        <h2>Recommended For You</h2>
        <div class="anime-grid">
            <?php foreach ($recommendedAnimes as $anime): ?>
                <a href="anime-info.php?animeId=<?= $anime['id'] ?>" class="anime-card">
                    <img src="<?= $anime['poster'] ?>" alt="<?= htmlspecialchars($anime['name']) ?>">
                    <div class="card-info">
                        <h3><?= htmlspecialchars($anime['name']) ?></h3>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Related Animes -->
   <section class="related-anime-nav">
    <h2>Related Anime</h2>
    <div class="related-list">
        <?php foreach ($relatedAnimes as $anime): ?>
            <div class="related-item">
                <div class="related-poster">
                     <a href="anime-info.php?animeId=<?= $anime['id'] ?>" class="anime-card">
                    <img src="<?= $anime['poster'] ?>" alt="<?= htmlspecialchars($anime['name']) ?>">
                    </a>
                </div>
                <div class="related-info">
                    <h3><?= htmlspecialchars($anime['name']) ?></h3>
                    <div class="anime-tags">
                        <span class="tag"><?= htmlspecialchars($anime['type']) ?></span>
                        <span class="tag"><?= htmlspecialchars($anime['episodes']) ?> Episodes</span>
                    </div>
                </div>
               
               
            </div>
        <?php endforeach; ?>
    </div>
</section>
</html>
