<h2>Nuevo contacto de propiedad</h2>
<p><strong>Propiedad:</strong> {{ $lead->property?->titulo ?? '—' }}</p>
<p><strong>Nombre:</strong> {{ $lead->name }}</p>
<p><strong>Email:</strong> {{ $lead->email }}</p>
<p><strong>Teléfono:</strong> {{ $lead->phone ?? '—' }}</p>
<p><strong>Mensaje:</strong> {{ $lead->message ?? '—' }}</p>
<p><strong>Fecha:</strong> {{ $lead->created_at->format('Y-m-d H:i') }}</p>
<p><strong>Origen:</strong> {{ $lead->source_page ?? '—' }}</p>
