<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Calendar Events</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Google Calendar Events</h1>

        <!-- Display success messages if any -->
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="post" action="{{ route('google.events.add') }}">
            @csrf
            <div class="mb-3">
                <label for="event_name" class="form-label">Event Name:</label>
                <input type="text" class="form-control" name="event_name" required>
            </div>

            <div class="mb-3">
                <label for="event_description" class="form-label">Event Description:</label>
                <textarea class="form-control" name="event_description" required></textarea>
            </div>

            <div class="mb-3">
                <label for="event_start_datetime" class="form-label">Event Start Date and Time:</label>
                <input type="datetime-local" class="form-control" name="event_start_datetime" required>
            </div>

            <div class="mb-3">
                <label for="event_end_datetime" class="form-label">Event End Date and Time:</label>
                <input type="datetime-local" class="form-control" name="event_end_datetime" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>


        <h2 class="mt-4">Your Events</h2>
        @if (count($events) > 0)
            <table class="table">
                <!-- Table header with column names -->
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Event Description</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- Table body with event details -->
                <tbody>
                    @foreach ($events->getItems() as $event)
                        <tr>
                            <!-- Display event details in each row -->
                            <td>{{ $event->getSummary() }}</td>
                            <td>{{ $event->getDescription() }}</td>
                            <td>{{ $event->getStart()->getDateTime() }}</td>
                            <td>{{ $event->getEnd()->getDateTime() }}</td>
                            <td>
                                <!-- Button to open the single modal for event updates -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#editModal" data-event-id="{{ $event->getId() }}"
                                    data-event-summary="{{ $event->getSummary() }}"
                                    data-event-description="{{ $event->getDescription() }}">Edit
                                </button>

                                <!-- Form to submit a DELETE request for the event -->
                                <form class="d-inline" method="post"
                                    action="{{ route('google.events.delete', ['eventId' => $event->getId()]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No events found.</p>
        @endif
    </div>

    <!-- Single Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form fields for updating event details -->
                    <form method="post" id="updateEventForm">
                        <form method="post" id="updateEventForm">
                            @csrf
                            <div class="mb-3">
                                <label for="updated_event_name" class="form-label">Updated Event Name:</label>
                                <input type="text" class="form-control" name="updated_event_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="updated_event_description" class="form-label">Updated Event
                                    Description:</label>
                                <textarea class="form-control" name="updated_event_description" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="updated_event_start_datetime" class="form-label">Updated Event Start Date
                                    and Time:</label>
                                <input type="datetime-local" class="form-control" name="updated_event_start_datetime"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="updated_event_end_datetime" class="form-label">Updated Event End Date and
                                    Time:</label>
                                <input type="datetime-local" class="form-control" name="updated_event_end_datetime"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Event</button>
                        </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and Popper.js for modal functionality -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        // Event listener to update modal fields when the Edit button is clicked
        $('#editModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var eventId = button.data('event-id');
            var eventSummary = button.data('event-summary');
            var eventDescription = button.data('event-description');

            // Update modal fields with event details
            $('#updateEventForm input[name="updated_event_name"]').val(eventSummary);
            $('#updateEventForm textarea[name="updated_event_description"]').val(eventDescription);

            // Update form action URL with the event ID
            $('#updateEventForm').attr('action', '/google/events/update/' + eventId);
        });
    </script>
</body>

</html>
