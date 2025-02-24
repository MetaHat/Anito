<?php
require 'tohost.php';

// Get the query and optional filters
$query = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Build the API URL
$endpoint = '/api/v2/hianime/search';
$apiUrl = BASE_API_URL . $endpoint . '?q=' . urlencode($query) . '&page=' . $page;

// Fetch search results
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if (!$data || !$data['success']) {
    die("Failed to fetch search results.");
}

$searchResults = $data['data']['animes'];
$currentPage = $data['data']['currentPage'];
$totalPages = $data['data']['totalPages'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Anito</title>
    <!--<link rel="stylesheet" href="styles.css">-->
    <style>
/* Light Anime-Themed Search Results */
body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #f6f7fb, #e3eaf7);
    color: #333;
    margin: 0;
    padding: 0;
}

/* Search Results Section */
.section {
    max-width: 900px;
    margin: 40px auto;
    background: rgba(255, 255, 255, 0.95);
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0, 102, 255, 0.2);
}

h2 {
    font-size: 24px;
    font-weight: 600;
    text-align: center;
    color: #0077ff;
    text-shadow: 0 0 8px rgba(0, 119, 255, 0.3);
}

/* Anime Grid */
.anime-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

/* Anime Cards */
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
    box-shadow: 0 0 8px rgba(0, 102, 255, 0.3);
    transition: transform 0.3s ease;
}

.anime-card img:hover {
    transform: scale(1.05);
}

.card-info {
    padding: 8px;
    background: rgba(240, 240, 255, 0.7);
    border-radius: 5px;
}

.card-info h3 {
    font-size: 16px;
    color: #0055ff;
    margin: 5px 0;
}

/* Pagination */
.pagination {
    text-align: center;
    margin-top: 20px;
}

.pagination a {
    padding: 10px 15px;
    margin: 5px;
    text-decoration: none;
    background: #0077ff;
    color: white;
    border-radius: 5px;
    transition: 0.3s;
}

.pagination a:hover {
    background: #0055ff;
    box-shadow: 0 0 12px rgba(0, 102, 255, 0.5);
}

.pagination span {
    font-weight: 600;
    color: #333;
}

    </style>
</head>
<body>

<?php include 'header.html'; ?>

<main class="container">

    <section class="section">
        <h2>Search Results for "<?= htmlspecialchars($query) ?>"</h2>

        <?php if (empty($searchResults)): ?>
            <p>No results found. Try another search query.</p>
        <?php else: ?>
            <div class="anime-grid">
                <?php foreach ($searchResults as $anime): ?>
                    <a href="anime-info.php?animeId=<?= $anime['id'] ?>" class="anime-card">
                        <img src="<?= $anime['poster'] ?>" alt="<?= htmlspecialchars($anime['name']) ?>">
                        <div class="card-info">
                            <h3><?= htmlspecialchars($anime['name']) ?></h3>
                            <p>Type: <?= $anime['type'] ?></p>
                            <p>Rating: <?= $anime['rating'] ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
<div class="pagination">
    <?php if ($currentPage > 1): ?>
        <a href="search.php?q=<?= urlencode($query) ?>&page=<?= $currentPage - 1 ?>" class="page-btn">Previous</a>
    <?php endif; ?>

    <span>Page <?= $currentPage ?> of <?= $totalPages ?></span>

    <?php if ($currentPage < $totalPages): ?>
        <a href="search.php?q=<?= urlencode($query) ?>&page=<?= $currentPage + 1 ?>" class="page-btn">Next</a>
    <?php endif; ?>

    <!-- Go to Page Input -->
    <!--<form action="search.php" method="get" class="goto-page-form">-->
    <!--    <input type="hidden" name="q" value="<?= htmlspecialchars($query) ?>">-->
    <!--    <input type="number" name="page" min="1" max="<?= $totalPages ?>" placeholder="Go to page" required>-->
    <!--    <button type="submit">Go</button>-->
    <!--</form>-->
</div>

        <?php endif; ?>
    </section>
</main>

<footer>
   <center><p>&copy; 2024 Anime World. Powered by Tohost Cloud Services.</p></center> 
</footer>

</body>
</html>
