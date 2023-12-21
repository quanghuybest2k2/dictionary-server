# Server Dictionary App For IT

### Xây dựng api cho từ điển tiếng anh chuyên ngành công nghệ thông tin

[Front end (winform) link](https://github.com/quanghuybest2k2/DictionaryAppForIT)

### PHP Docblock

```php
<?php
// example
/**
     * Tính tổng số lượng bản ghi của cả hai loại.
     *
     * @param int $user_id ID người dùng
     * @return int Tổng số lượng bản ghi
     */
    public function TotalLoveItemOfUser($user_id): int
    {
        $loveVocabulary = $this->countLoveVocabulary($user_id);
        $loveText = $this->countLoveTexts($user_id);

        return $loveVocabulary + $loveText;
    }
?>
```
