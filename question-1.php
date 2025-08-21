<?php

function calculateDirectorySize(string $directoryPath): int
{
    $real = realpath($directoryPath);
    if ($real === false || !is_dir($real)) {
        return 0;
    }

    $size = 0;

    // Skip dot entries, return SplFileInfo objects; do not follow symlinks to avoid loops
    $flags =
        FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_FILEINFO;

    $dirIter = new RecursiveDirectoryIterator($real, $flags);

    // LEAVES_ONLY returns only files; CATCH_GET_CHILD ignores unreadable subdirs
    $iter = new RecursiveIteratorIterator(
        $dirIter,
        RecursiveIteratorIterator::LEAVES_ONLY,
        RecursiveIteratorIterator::CATCH_GET_CHILD
    );

    foreach ($iter as $fileInfo) {
        // Exclude directories and symlinks; count only regular files
        if ($fileInfo->isFile() && !$fileInfo->isLink()) {
            $size += $fileInfo->getSize();
        }
    }

    return $size;
}


echo calculateDirectorySize("/Users/juno/Desktop");
