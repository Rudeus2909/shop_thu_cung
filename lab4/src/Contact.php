<?php

namespace CT275\Labs;

class Contact
{
	private $db;

	private $id = -1;
	public $name;
	public $phone;
	public $notes;
	public $created_at;
	public $updated_at;
	private $errors = [];

	public function getId()
	{
		return $this->id;
	}

	public function __construct($pdo)
	{
		$this->db = $pdo;
	}

	public function fill(array $data)
	{
		if (isset($data['name'])) {
			$this->name = trim($data['name']);
		}

		if (isset($data['phone'])) {
			$this->phone = preg_replace('/\D+/', '', $data['phone']);
		}

		if (isset($data['notes'])) {
			$this->notes = trim($data['notes']);
		}

		return $this;
	}

	public function getValidationErrors()
	{
		return $this->errors;
	}

	public function validate()
	{
		if (!$this->name) {
			$this->errors['name'] = 'Invalid name.';
		}

		if (strlen($this->phone) < 10 || strlen($this->phone) > 11) {
			$this->errors['phone'] = 'Invalid phone number.';
		}

		if (strlen($this->notes) > 255) {
			$this->errors['notes'] = 'Notes must be at most 255 characters.';
		}

		return empty($this->errors);
	}
	public function all()
	{
		$contacts = [];
		$statement = $this->db->prepare('select * from contacts');
		$statement->execute();
		while ($row = $statement->fetch()) {
			$contact = new Contact($this->db);
			$contact->fillFromDB($row);
			$contacts[] = $contact;
		}

		return $contacts;
	}

	protected function fillFromDB(array $row)
	{
		[
			'id' => $this->id,
			'name' => $this->name,
			'phone' => $this->phone,
			'notes' => $this->notes,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at
		] = $row;
		return $this;
	}

	public function save()
	{
		$result = false;
		if ($this->id >= 0) {
			$statement = $this->db->prepare(
				'update contacts set name = :name,
					phone = :phone, notes = :notes, updated_at = now()
					where id = :id'
			);

			$result = $statement->execute([
				'name' => $this->name,
				'phone' => $this->phone,
				'notes' => $this->notes,
				'id' => $this->id]);
		} else {
			$statement = $this->db->prepare(
				'insert into contacts (name, phone, notes, created_at, updated_at)
					values (:name, :phone, :notes, now(), now())'
			);

			$result = $statement->execute([
				'name' => $this->name,
				'phone' => $this->phone,
				'notes' => $this->notes
			]);
			
			if($result) {
				$this->id =$this->db->lastInsertId();
			}
		}
		return $result;
	}

	public function find($id)
	{
		$statement = $this->db->prepare('select * from contacts where id = :id');
		$statement->execute(['id' => $id]);

		if ($row = $statement->fetch()) {
			$this->fillFromDB($row);
			return $this;
		}

		return null;
	}

	public function update(array $data)
	{
		$this->fill($data);
		if ($this->validate()) {
			return $this->save();
		}
		return false;
	}

	public function delete()
	{
		$statement = $this->db->prepare('delete from contacts where id = :id');
		return $statement->execute(['id' => $this->id]);
	}
}
