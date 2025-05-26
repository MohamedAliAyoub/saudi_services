<div
        x-data="{
            currentIndex: 0,
            images: @js($this->getImages()),
            autoplayInterval: null,
            startAutoplay() {
                this.autoplayInterval = setInterval(() => {
                    this.next();
                }, 3000);
            },
            stopAutoplay() {
                clearInterval(this.autoplayInterval);
            },
            next() {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
            },
            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
            },
            goTo(index) {
                this.currentIndex = index;
            }
        }"
        x-init="startAutoplay()"
        class="relative w-full overflow-hidden"
        style="height: 50vh; width: 900px;">
    {{--    style="height: 50vh; width: 95vw;max-width: 100vw;">--}}


    >
        <!-- Slide -->
        <template x-for="(item, index) in images" :key="index">
            <div
                x-show="currentIndex === index"
                class="absolute inset-0 transition-opacity duration-500 ease-in-out"
            >
                <img
                    :src="item.image"
                    class="w-full h-full object-cover"
                    :alt="item.title"
                />
                <template x-if="item.title">
                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white p-4">
                        <h3 class="text-lg font-bold" x-text="item.title"></h3>
                        <p class="text-sm" x-text="item.description"></p>
                    </div>
                </template>
            </div>
        </template>

        <!-- Arrows -->
        <button @click="prev"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full text-sm">
            ‹
        </button>
        <button @click="next"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 text-white p-2 rounded-full text-sm">
            ›
        </button>

        <!-- Pagination -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
            <template x-for="(item, index) in images" :key="index">
                <div
                    @click="goTo(index)"
                    :class="{
                        'bg-white': currentIndex === index,
                        'bg-gray-400': currentIndex !== index
                    }"
                    class="w-3 h-3 rounded-full cursor-pointer transition-all"
                ></div>
            </template>
        </div>
    </div>
