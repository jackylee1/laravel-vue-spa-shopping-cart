export function sort(arr) {
  return arr.sort(function(a, b) {
    return a.sorting_order - b.sorting_order;
  });
}