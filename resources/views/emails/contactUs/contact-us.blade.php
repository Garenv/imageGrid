<div class="container">
    <p>Full Name: {{ $emailData['from'] ?? "" }}</p>
    <p>Email: {{ $emailData['email'] ?? "" }}</p>
    <p>Message: {{ $emailData['messageText']  ?? "" }}</p>
</div>
