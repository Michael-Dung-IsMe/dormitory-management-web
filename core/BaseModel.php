<?php

class BaseModel {
    protected $conn;

    // Subclass phải khai báo các thuộc tính này
    protected $tableClause   = '';  // VD: "Student" hoặc "Room r"
    protected $selectClause  = '*'; // VD: "r.*, (SELECT ...) as occupancy"
    protected $joinClause    = '';  // VD: "JOIN Student s ON ..."
    protected $searchColumns = [];  // VD: ['full_name', 'student_code']
    protected $orderBy       = '';  // VD: "student_id DESC"

    public function __construct($db) {
        $this->conn = $db;
    }

    public function count(string $search = ''): int {
        $query = "SELECT COUNT(*) as total FROM {$this->tableClause} {$this->joinClause}";

        if (!empty($search) && !empty($this->searchColumns)) {
            $conditions = array_map(fn($col) => "$col LIKE :search", $this->searchColumns);
            $query .= " WHERE " . implode(' OR ', $conditions);
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($search) && !empty($this->searchColumns)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->execute();
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function read(string $search = '', int $limit = 25, int $offset = 0) {
        $query = "SELECT {$this->selectClause} FROM {$this->tableClause} {$this->joinClause}";

        if (!empty($search) && !empty($this->searchColumns)) {
            $conditions = array_map(fn($col) => "$col LIKE :search", $this->searchColumns);
            $query .= " WHERE " . implode(' OR ', $conditions);
        }

        $query .= " ORDER BY {$this->orderBy} LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($query);

        if (!empty($search) && !empty($this->searchColumns)) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->bindParam(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
}
?>
