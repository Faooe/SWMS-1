<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-5">

    <x-ui.stat-card

        title="Total Office"

        :value="$statistics['total']"

        icon="building-2"

        color="blue"

    />

    <x-ui.stat-card

        title="Active"

        :value="$statistics['active']"

        icon="badge-check"

        color="green"

    />

    <x-ui.stat-card

        title="Inactive"

        :value="$statistics['inactive']"

        icon="badge-x"

        color="red"

    />

    <x-ui.stat-card

        title="Head Office"

        :value="$statistics['head_office']"

        icon="building"

        color="purple"

    />

    <x-ui.stat-card

        title="Employees"

        :value="$statistics['employees']"

        icon="users"

        color="orange"

    />

</div>