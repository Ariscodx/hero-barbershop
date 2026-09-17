<?php
// Fix permissions script - DELETE THIS FILE AFTER USE!
function fixPermissions($path) {
    $count_dirs = 0;
    $count_files = 0;
    $items = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($items as $item) {
        if ($item->isDir()) {
            chmod($item->getPathname(), 0755);
            $count_dirs++;
        } else {
            chmod($item->getPathname(), 0644);
            $count_files++;
        }
    }

    return ['dirs' => $count_dirs, 'files' => $count_files];
}

$basePath = dirname(__DIR__) . '/hero-barbershop';
$result = fixPermissions($basePath);

echo "<h2 style='color:green'>✅ Permission Fix Complete!</h2>";
echo "<p>Fixed <strong>{$result['dirs']}</strong> folders → 755</p>";
echo "<p>Fixed <strong>{$result['files']}</strong> files → 644</p>";
echo "<hr>";
echo "<p style='color:red'><strong>⚠️ PENTING: Segera hapus file ini dari server setelah berhasil!</strong></p>";
echo "<p>Hapus file <code>fixpermission.php</code> dari folder <code>public_html</code> kamu sekarang.</p>";
