<?php
require 'tohost.php';

if (!isset($_GET['animeId']) || empty($_GET['animeId'])) {
    die("Anime ID is required.");
}
include 'header.html';

$animeId = htmlspecialchars($_GET['animeId']);

// Endpoint for fetching episodes
$endpoint = '/api/v2/hianime/anime/' . $animeId . '/episodes';
$apiUrl = BASE_API_URL . $endpoint;

// Fetch data from API
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if (!$data || !$data['success']) {
    die("Failed to fetch episodes.");
}

$episodes = $data['data']['episodes'];
$totalEpisodes = $data['data']['totalEpisodes'];

// Verify the fetched data
if (empty($episodes)) {
    die("No episodes found.");
}

// **Group episodes into sections dynamically (e.g., 50 per group)**
$groupSize = 50;
$groupedEpisodes = [];
foreach ($episodes as $episode) {
    $groupIndex = floor(($episode['number'] - 1) / $groupSize);
    $groupedEpisodes[$groupIndex][] = $episode;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anime Stream - <?= htmlspecialchars($animeId) ?></title>
    <style>
/* 🌟 Main Container */
.container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

/* 🌟 Episode Navigation */
.episode-nav {
    background: #fff;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* 🌟 Collapsible Sections (Using details/summary) */
.episode-section details {
    border-radius: 6px;
    margin-bottom: 10px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.episode-section summary {
    background: #ff6600;
    color: white;
    padding: 12px;
    font-size: 18px;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

.episode-section summary:hover {
    background: #e65100;
}

/* 🌟 Episode Grid */
.episode-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 10px;
    padding: 10px;
    background: #fff;
}

.episode-card {
    background: #ffcc80;
    padding: 10px;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    transition: 0.3s;
    text-align: center;
    font-size: 16px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.episode-card:hover {
    background: #ffb74d;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* 🌟 Filler Tag */
.filler-tag {
    display: inline-block;
    background: red;
    color: white;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
}

/* 🌟 Player */
.player-container {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    margin-top: 20px;
}

#episode-player {
    width: 100%;
    height: 100%;
    border: none;
    border-radius: 6px;
}

/* 🌟 Player Options */
.player-options {
    display: flex;
    justify-content: center;
    gap: 20px;
    margin-top: 10px;
}

.player-options select {
    padding: 8px;
    font-size: 16px;
    border-radius: 6px;
    border: 2px solid #ff6600;
}
    </style>
</head>
<body>
    <main class="container">
        <!-- Episode Navigation -->
        <nav class="episode-nav">
            <h2>Episodes</h2>
            <?php foreach ($groupedEpisodes as $groupIndex => $group): ?>
                <section class="episode-section">
                    <details>
                        <summary>
                            Episodes <?= $groupIndex * $groupSize + 1 ?> - <?= min(($groupIndex + 1) * $groupSize, $totalEpisodes) ?>
                        </summary>
                        <div class="episode-grid">
                            <?php foreach ($group as $episode): ?>
                                <a href="#" class="episode-card" 
                                   data-episode-id="<?= $episode['episodeId'] ?>" 
                                   onclick="loadEpisode(event, '<?= $episode['episodeId'] ?>')">
                                    <div class="episode-number">Ep <?= $episode['number'] ?></div>
                                    <div class="episode-title"><?= htmlspecialchars($episode['title']) ?></div>
                                    <?php if ($episode['isFiller']): ?>
                                        <span class="filler-tag">Filler</span>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </details>
                </section>
            <?php endforeach; ?>
        </nav>

        <!-- Player -->
        <section class="player-container">
            <iframe id="episode-player" src="<?= !empty($episodes[0]['episodeId']) ? 'player.php?episodeId=' . $episodes[0]['episodeId'] . '&category=sub' : '' ?>"></iframe>
            <div class="player-options">
                <div>
                    <label for="language-select">Language:</label>
                    <select id="language-select" onchange="updatePlayer()">
                        <option value="sub">Sub</option>
                        <option value="dub">Dub</option>
                    </select>
                </div>
                <div>
                    <label for="server-select">Server:</label>
                    <select id="server-select" onchange="updatePlayer()">
                        <option value="player">Server 1</option>
                        <option value="player2">Server 2</option>
                    </select>
                </div>
            </div>
        </section>

        <?php include 'detailstream.php'; ?>
    </main>

    <script>
        // Load and update player
        let currentEpisodeId = "<?= !empty($episodes[0]['episodeId']) ? $episodes[0]['episodeId'] : '' ?>";

        function loadEpisode(event, episodeId) {
            event.preventDefault();
            currentEpisodeId = episodeId;
            updatePlayer();
        }

        function updatePlayer() {
            const language = document.getElementById('language-select').value;
            const server = document.getElementById('server-select').value;
            const player = document.getElementById('episode-player');
            player.src = `${server}.php?episodeId=${currentEpisodeId}&category=${language}`;
        }
    </script>
</body>
</html>
