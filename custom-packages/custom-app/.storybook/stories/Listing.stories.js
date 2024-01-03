import {
  Listing,
  ListingHeaderCell,
  ListingDataCell,
  Avatar,
  Multiselect,
  Button,
} from "craftable-pro/Components";
import {
  CheckCircleIcon,
  ChevronRightIcon,
  XCircleIcon,
} from "@heroicons/vue/24/outline";
import { Link } from "@inertiajs/vue3";
import MDXDocumentation from "./Listing.mdx";

export default {
  title: "Components/Listing/Listing",
  component: Listing,
  parameters: {
    docs: {
      page: MDXDocumentation,
    },
  },
  argTypes: {
    data: {
      type: "object",
      defaultValue: {
        total: 50,
        per_page: 15,
        current_page: 1,
        last_page: 4,
        first_page_url: "http://laravel.app?page=1",
        last_page_url: "http://laravel.app?page=1",
        next_page_url: null,
        prev_page_url: null,
        path: "http://laravel.app",
        from: 1,
        to: 15,
        data: [
          {
            id: 1,
            first_name: "Lorem",
            last_name: "Ipsum",
            email: "test@email.com",
            email_verified_at: "2020-01-01",
            resource_url: "#",
          },
          {
            id: 2,
            first_name: "Lorem",
            last_name: "Dolor",
            email: "test@email.com",
            email_verified_at: null,
            resource_url: "#",
          },
          {
            id: 3,
            first_name: "Amet",
            last_name: "Mrkvicka",
            email: "test@email.com",
            email_verified_at: "2020-01-01",
            resource_url: "#",
          },
        ],
        links: [
          {
            label: "Previous",
            url: null,
            active: false,
          },
          {
            label: "1",
            url: null,
            active: true,
          },
          {
            label: "2",
            url: null,
            active: false,
          },
          {
            label: "3",
            url: null,
            active: false,
          },
          {
            label: "Next",
            url: null,
            active: false,
          },
        ],
      },
    },
    columns: {
      type: "array",
      defaultValue: ["id", "first_name", "last_name", "email"],
    },
    withBulkSelect: {
      type: "boolean",
      defaultValue: true,
    },
  },
};

export const Default = (args) => ({
  components: { Listing },
  setup() {
    return { args };
  },
  template: '<Listing :data="args.data" :columns="args.columns" />',
});

export const WithSlots = (args) => ({
  components: {
    Listing,
    ListingHeaderCell,
    ListingDataCell,
    Avatar,
    CheckCircleIcon,
    ChevronRightIcon,
    Link,
    XCircleIcon,
    Multiselect,
    Button,
  },
  setup() {
    return { args };
  },
  template: `
    <Listing :data="args.data" :filters="{ id: null }">
      <template #filters="{ filtersForm, resetFilters }">
        <div class="space-y-4">
          <Multiselect
            v-model="filtersForm.id"
            name="id"
            label="ID"
            :options="[1,2,3]"
          />
          <!-- more inputs/filters can be here -->

          <Button @click="resetFilters"> Vyresetovat filtre </Button>
        </div>
      </template>
      <template #tableHead>
        <ListingHeaderCell> ID </ListingHeaderCell>
        <ListingHeaderCell> Name </ListingHeaderCell>
        <ListingHeaderCell> Verified </ListingHeaderCell>
        <ListingHeaderCell>
          <span class="sr-only">Edit</span>
        </ListingHeaderCell>
      </template>
      <template #tableRow="{ item }">
        <ListingDataCell>
          {{ item.id }}
        </ListingDataCell>
        <ListingDataCell>
          <div class="flex items-center">
            <Avatar :src="item.avatar_url" :initials="item.initials" />
            <div class="ml-4">
              <div class="font-medium text-gray-900">
                {{ item.first_name }} {{ item.last_name }}
              </div>
              <div class="text-gray-500">{{ item.email }}</div>
            </div>
          </div>
        </ListingDataCell>
        <ListingDataCell>
          <CheckCircleIcon
            v-if="item.email_verified_at"
            class="inline h-5 w-5 text-green-600"
          />
          <XCircleIcon v-else class="inline h-5 w-5 text-red-600" />
        </ListingDataCell>
        <ListingDataCell>
          <Link
            :href="item.resource_url"
            class="text-gray-500 hover:text-gray-700"
          >
            <ChevronRightIcon class="ml-auto h-5 w-5" />
          </Link>
        </ListingDataCell>
      </template>
    </Listing>
  `,
});
