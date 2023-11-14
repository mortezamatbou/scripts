<?php

interface DatabaseStorageInterface
{
    public function save(array $items): bool;
}

class DatabaseStorageMySQL implements DatabaseStorageInterface
{

    public function save(array $items): bool
    {
        echo "Save to MySQL.\n";
        return TRUE;
    }

}

class DatabaseStorageMongoDB implements DatabaseStorageInterface
{

    public function save(array $items): bool
    {
        echo "Save to MongoDB.\n";
        return TRUE;
    }

}


class DatabaseStorage
{

    private array $items;
    private DatabaseStorageInterface $storage_interface;


    public function set_items(array $items): DatabaseStorage
    {
        $this->items = $items;
        return $this;
    }

    public function set_database_interface(DatabaseStorageInterface $storage_interface): DatabaseStorage
    {
        $this->storage_interface = $storage_interface;
        return $this;
    }

    public function get_items(): array
    {
        return $this->items ?? [];
    }

    /**
     * @throws Exception
     */
    public function save(): void
    {
        if (!$this->items) {
            throw new Exception("Items is empty.");
        }

        if (!$this->storage_interface) {
            throw new Exception("Not selected storage interface.");
        }

        $this->storage_interface->save($this->items);
    }

}

$database_storage = new DatabaseStorage();

try {

    $items = [
        ['name' => 'Ali', 'age' => 22],
        ['name' => 'Reza', 'age' => 26],
        ['name' => 'Hossein', 'age' => 23]
    ];

    $database_storage->set_items($items)->set_database_interface(new DatabaseStorageMySQL())->save();
    $database_storage->set_database_interface(new DatabaseStorageMongoDB())->save();

} catch (Exception $ex) {
    print_r($ex->getMessage());
}

