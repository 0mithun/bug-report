<?php
namespace App\Entity;
class BugReport extends Entity {
    private $id;
    private $report_type;
    private $email;
    private $link;
    private $message;
    private $created_at;

    public function getId(): int {
        return $this->id;
    }

    public function toArray(): array{
        return [
            'report_type' => $this->getReportType(),
            'email'       => $this->getEmail(),
            'link'        => $this->getLink(),
            'message'     => $this->getMessage(),
        ];
    }

    /**
     * Set $id
     */
    public function setId( $id ) {
        $this->id = $id;
    }

    public function setReportType( string $type ): BugReport {
        $this->report_type = $type;

        return $this;
    }

    public function getReportType(): string {
        return $this->report_type;
    }

    /**
     * Set $email
     */
    public function setEmail( string $email ) {
        $this->email = $email;

        return $this;
    }

    /**
     * Set $link
     */
    public function setLink( ?string $link ) {
        $this->link = $link;

        return $this;
    }

    /**
     * Set $message
     */
    public function setMessage( string $message ) {
        $this->message = $message;

        return $this;
    }

    /**
     * Get $email
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Get $link
     */
    public function getLink(): ?string {
        return $this->link;
    }

    /**
     * Get $message
     */
    public function getMessage(): string {
        return $this->message;
    }

    public function setCreatedAt( $created_at ) {
        $this->created_at = $created_at;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

}
