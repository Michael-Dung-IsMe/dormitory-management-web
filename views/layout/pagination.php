<?php if (isset($total_pages) && $total_pages > 1): ?>
<div class="pagination-container" style="margin-top: 20px; display: flex; justify-content: flex-end;">
    <ul class="pagination" style="display: flex; list-style: none; gap: 5px; padding: 0; margin: 0;">
        <?php 
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $controllerName = explode('/', trim($uri, '/'))[0] ?: 'dashboard';
        $searchParam = isset($search) ? $search : '';
        for ($i = 1; $i <= $total_pages; $i++): 
        ?>
            <li>
                <a href="/<?php echo htmlspecialchars($controllerName); ?>?search=<?php echo urlencode($searchParam); ?>&page=<?php echo $i; ?>" 
                   style="padding: 6px 12px; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: <?php echo (isset($page) && $i == $page) ? '#fff' : '#4361ee'; ?>; background-color: <?php echo (isset($page) && $i == $page) ? '#4361ee' : '#fff'; ?>;">
                    <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>
    </ul>
</div>
<?php endif; ?>
