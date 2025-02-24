<?php
require 'tohost.php';

// Get animeId from the URL
if (!isset($_GET['animeId']) || empty($_GET['animeId'])) {
    die("Anime ID is required.");
}
include 'header.html'; 

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($anime['name']) ?> - Anime Info</title>
    <!--<link rel="stylesheet" href="infostyle.css"> -->
    <style>
        /* General Reset */
/* Lightened HiAnime-Inspired Theme */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f6f7fb, #e3eaf7);
    color: #333;
    margin: 0;
    padding: 0;
}

/* Anime Details Section */
.anime-details {
    max-width: 900px;
    margin: 40px auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 102, 255, 0.2);
}

/* Title */
.anime-title {
    font-size: 28px;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
    color: #0077ff;
    text-shadow: 0 0 8px rgba(0, 119, 255, 0.3);
}

/* Anime Info Section */
.anime-info {
    display: flex;
    gap: 20px;
    margin-top: 20px;
}

.anime-poster img {
    width: 250px;
    border-radius: 10px;
    box-shadow: 0 0 12px rgba(0, 102, 255, 0.3);
    transition: transform 0.3s ease;
}

.anime-poster img:hover {
    transform: scale(1.05);
}

.anime-meta {
    flex: 1;
    font-size: 16px;
    background: rgba(240, 240, 255, 0.7);
    padding: 15px;
    border-radius: 10px;
}

/* Anime Stats */
.anime-meta p {
    margin: 5px 0;
    font-weight: 400;
}

.anime-meta strong {
    color: #0055ff;
}

/* Seasons Section */
.seasons {
    margin-top: 30px;
}

.anime-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 15px;
}

.anime-card {
    text-decoration: none;
    background: rgba(0, 102, 255, 0.1);
    padding: 10px;
    border-radius: 8px;
    text-align: center;
    transition: 0.3s;
}

.anime-card img {
    width: 100%;
    border-radius: 5px;
}

.anime-card:hover {
    background: rgba(0, 102, 255, 0.2);
    transform: scale(1.05);
}

/* Watch Button */
.watch-btn {
    display: block;
    text-align: center;
    margin-top: 20px;
    padding: 12px;
    background: linear-gradient(90deg, #0077ff, #0055ff);
    color: #fff;
    text-decoration: none;
    font-weight: 600;
    border-radius: 50px;
    box-shadow: 0 0 12px rgba(0, 102, 255, 0.5);
}

.watch-btn:hover {
    background: linear-gradient(90deg, #0055ff, #0033ff);
    box-shadow: 0 0 20px rgba(0, 102, 255, 0.7);
}

/* Recommended Anime */
.recommended-animes {
    margin-top: 50px;
}

.recommended-animes h2 {
    text-align: center;
    font-size: 22px;
    color: #0077ff;
}

/* Related Anime Sidebar */
.related-anime-nav {
    background: rgba(255, 255, 255, 0.9);
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 102, 255, 0.2);
    margin-top: 40px;
}

.related-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.related-item {
    display: flex;
    align-items: center;
    background: rgba(0, 102, 255, 0.1);
    padding: 10px;
    border-radius: 8px;
    transition: 0.3s;
}

.related-item:hover {
    background: rgba(0, 102, 255, 0.15);
}

.related-poster img {
    width: 60px;
    border-radius: 5px;
    margin-right: 10px;
}

.related-info {
    flex: 1;
}

.anime-tags {
    display: flex;
    gap: 5px;
    font-size: 12px;
}

.tag {
    background: #0077ff;
    padding: 3px 6px;
    border-radius: 3px;
    font-weight: bold;
}

/* Add Button */
.add-btn {
    background: #0077ff;
    color: #fff;
    padding: 5px 10px;
    border-radius: 50%;
    text-decoration: none;
    font-size: 20px;
    font-weight: bold;
}

    </style>
</head>
<body>

<main class="container">
    <!-- Anime Details -->
    <section class="section anime-details">
        <h2 class="anime-title"><?= htmlspecialchars($anime['name']) ?></h2>
        <div class="anime-info">
             <div class="anime-poster">
                <img src="<?= $anime['poster'] ?>" alt="<?= htmlspecialchars($anime['name']) ?>">
             </div>
            <div class="anime-meta">
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
            
            
            
            
            <a href="streaming.php?animeId=<?= $animeId ?>" class="watch-btn">Watch Episodes</a>
        </div>
</div>

    </section>
<!--    <section class="section more-info">-->
<!--    <h2>More Info</h2>-->
<!--    <ul>-->
<!--        <li>Aired: <?= htmlspecialchars($moreInfo['aired']) ?></li>-->
<!--        <li>Genres: <?= implode(', ', $moreInfo['genres']) ?></li>-->
<!--        <li>Status: <?= htmlspecialchars($moreInfo['status']) ?></li>-->
<!--        <li>Studios: <?= htmlspecialchars($moreInfo['studios']) ?></li>-->
<!--    </ul>-->
<!--</section>-->



<!--<section class="section promotional-videos">-->
<!--    <h2>Promotional Videos</h2>-->
<!--    <div class="video-grid">-->
<!--        <?php foreach ($promotionalVideos as $video): ?>-->
<!--            <div class="video-card">-->
<!--                <img src="<?= $video['thumbnail'] ?>" alt="<?= htmlspecialchars($video['title']) ?>">-->
<!--                <h3><?= htmlspecialchars($video['title']) ?></h3>-->
<!--                <a href="<?= $video['source'] ?>" target="_blank">Watch Video</a>-->
<!--            </div>-->
<!--        <?php endforeach; ?>-->
<!--    </div>-->
<!--</section>-->

<!--<section class="section character-voice-actors">-->
<!--    <h2>Character Voice Actors</h2>-->
<!--    <div class="character-grid">-->
<!--        <?php foreach ($characterVoiceActors as $entry): ?>-->
<!--            <div class="character-card">-->
<!--                <h3>Character</h3>-->
<!--                <img src="<?= $entry['character']['poster'] ?>" alt="<?= htmlspecialchars($entry['character']['name']) ?>">-->
<!--                <p><?= htmlspecialchars($entry['character']['name']) ?> (<?= htmlspecialchars($entry['character']['cast']) ?>)</p>-->
<!--                <h3>Voice Actor</h3>-->
<!--                <img src="<?= $entry['voiceActor']['poster'] ?>" alt="<?= htmlspecialchars($entry['voiceActor']['name']) ?>">-->
<!--                <p><?= htmlspecialchars($entry['voiceActor']['name']) ?> (<?= htmlspecialchars($entry['voiceActor']['cast']) ?>)</p>-->
<!--            </div>-->
<!--        <?php endforeach; ?>-->
<!--    </div>-->
<!--</section>-->

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
   <aside class="related-anime-nav">
    <h2>Related Anime</h2>
    <div class="related-list">
        <?php foreach ($relatedAnimes as $anime): ?>
            <div class="related-item">
                <div class="related-poster">
                    <img src="<?= $anime['poster'] ?>" alt="<?= htmlspecialchars($anime['name']) ?>">
                </div>
                <div class="related-info">
                    <h3><?= htmlspecialchars($anime['name']) ?></h3>
                    <div class="anime-tags">
                        <span class="tag"><?= htmlspecialchars($anime['type']) ?></span>
                        <span class="tag"><?= htmlspecialchars($anime['episodes']) ?> Episodes</span>
                    </div>
                </div>
                <a href="anime-info.php?animeId=<?= $anime['id'] ?>" class="add-btn">+</a>
            </div>
        <?php endforeach; ?>
    </div>
</aside>


</main>

<?php include 'footer.html'; ?>

</body>
</html>
