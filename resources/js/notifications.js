export function initNotifications(Alpine) {

    // Store global biar unread count kesinkron di semua tempat,
    // dan polling cuma jalan sekali walau navbar-nya re-render
    // (misal abis Livewire navigate).
    Alpine.store("notifications", {
        unreadCount: 0,
        polling: false,

        async fetchUnreadCount(url) {
            try {
                const res = await fetch(url, {
                    headers: { Accept: "application/json" },
                });
                const data = await res.json();
                this.unreadCount = data.unread_count ?? 0;
            } catch (e) {
                // silent fail, jangan sampai ganggu UI kalau network gagal
            }
        },

        startPolling(url, intervalMs = 30000) {
            if (this.polling) return;
            this.polling = true;
            this.fetchUnreadCount(url);
            setInterval(() => this.fetchUnreadCount(url), intervalMs);
        },
    });

    Alpine.data("notificationDropdown", (config) => ({
        open: false,
        loading: false,
        items: [],

        init() {
            this.$store.notifications.startPolling(config.unreadCountUrl);
        },

        get unreadCount() {
            return this.$store.notifications.unreadCount;
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.fetchNotifications();
        },

        async fetchNotifications() {
            this.loading = true;
            try {
                const res = await fetch(config.indexUrl, {
                    headers: { Accept: "application/json" },
                });
                const data = await res.json();
                this.items = data.data ?? [];
            } catch (e) {
                this.items = [];
            } finally {
                this.loading = false;
            }
        },

        async openItem(item) {
            if (!item.is_read) {
                await this.markAsRead(item.id, true);
            }
            this.open = false;
            if (item.url) {
                window.location.href = item.url;
            }
        },

        async markAsRead(id, silent = false) {
            try {
                await fetch(config.readUrl.replace(":id", id), {
                    method: "PATCH",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                });

                const item = this.items.find((n) => n.id === id);
                if (item) item.is_read = true;

                this.$store.notifications.unreadCount = Math.max(
                    0,
                    this.$store.notifications.unreadCount - 1
                );
            } catch (e) {}
        },

        async markAllAsRead() {
            try {
                await fetch(config.readAllUrl, {
                    method: "PATCH",
                    headers: {
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                });

                this.items = this.items.map((n) => ({ ...n, is_read: true }));
                this.$store.notifications.unreadCount = 0;
            } catch (e) {}
        },

        timeAgo(dateStr) {
            const seconds = Math.floor(
                (Date.now() - new Date(dateStr).getTime()) / 1000
            );

            if (seconds < 60) return "Baru saja";

            const minutes = Math.floor(seconds / 60);
            if (minutes < 60) return `${minutes} menit lalu`;

            const hours = Math.floor(minutes / 60);
            if (hours < 24) return `${hours} jam lalu`;

            return `${Math.floor(hours / 24)} hari lalu`;
        },
    }));
}