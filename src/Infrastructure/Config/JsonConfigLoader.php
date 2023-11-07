<?php

namespace Infrastructure\Config;

class JsonConfigLoader
{
    public function loadFromDirectory(string $dir): array
    {
        $files = scandir($dir);

        $config = [];
        foreach ($files as $file) {
            if (preg_match('/^(.+)\.json$/', $file, $matches)) {
                $config[$matches[1]] = json_decode(file_get_contents("$dir/$file"), true);
            }
        }

        return $this->flattenToDotNotation('', $config);
    }

    private function flattenToDotNotation(string $base, array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $key = empty($base) ? $key : "$base.$key";

            if (is_array($value)) {
                $result = array_merge($result, $this->flattenToDotNotation($key, $value));
            } else {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}