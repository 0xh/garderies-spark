<div class="alert alert-warning mb-4" v-if="subscriptionIsOnTrial">
    {!! __('You are currently within your free trial period. Your trial will expire on :date.', ['date' => '<strong>' . auth()->user()->trial_ends_at->format('d.m.Y') . '</strong>']) !!}
</div>
