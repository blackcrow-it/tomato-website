<script>
    new Vue({
        el: '#comment__js',
        data: {
            comments: [],
            content: '',
            listContent: [],
            loadingComment: false,
            loading: true,
        },
        mounted() {
            this.getComments();
        },
        methods: {
            addComment(parent_id, content) {
                if (content) {
                    this.loadingComment = true;
                    axios.post("{{ route('api.comment.add') }}", {
                        object_id: {{ $id_object }},
                        type: '{{ $type_object }}',
                        parent_id: parent_id,
                        content: content
                    }).then(res => {
                        if (parent_id == null) {
                            this.comments.unshift(res.data);
                            this.content = '';
                        } else {
                            for (let i in this.comments){
                                if(this.comments[i].id == parent_id){
                                    this.comments[i].child.push(res.data);
                                    this.listContent[i] = '';
                                    break;
                                }
                            }
                        }
                    }).catch(err => {
                        // console.log(err);
                    }).finally(() => {
                        this.loadingComment = false;
                    });
                }
            },
            getComments() {
                this.loading = true;
                axios.get("{{ route('api.comment.getAll') }}", {
                    params: {
                        object_id: {{ $id_object }},
                        type: '{{ $type_object }}'
                    }
                }).then(res => {
                    this.comments = res.data;
                    res.data.forEach(element => {
                        this.listContent.unshift('');
                    });
                    // console.log(res);
                }).catch(err => {
                    // console.log(err);
                }).finally(() => {
                    this.loading = false;
                });
            },
            datetimeFormat(str) {
                return moment(str).format('YYYY-MM-DD HH:mm:ss');
            },
            approveComment(id, index, index_parent = null) {
                console.log(id, index, index_parent)
                if (index_parent != null) {
                    if (this.comments[index_parent].approved) {
                        document.body.classList.add('waiting');
                        axios.patch(
                            "api/comment/approve/" + id,
                        ).then(res => {
                            if(res.status == 'success') {
                                this.comments[index_parent].child[index].approved = true;
                            }
                        }).finally(() => {
                            this.loadingComment = false;
                            document.body.classList.remove('waiting');
                        })
                    }
                } else {
                    document.body.classList.add('waiting');
                    axios.patch(
                    "api/comment/approve/" + id,
                    ).then(res => {
                        if(res.status == 'success') {
                            this.comments[index].approved = true;
                        }
                    }).finally(() => {
                        this.loadingComment = false;
                        document.body.classList.remove('waiting');
                    })
                }
            },
            deleteComment(id, index, index_parent = null) {
                bootbox.confirm({
                    message: 'Bạn chắc chắn muốn xoá bình luận này?',
                    buttons: {
                        confirm: {
                            label: 'Xác nhận',
                            className: 'btn--sm btn--success'
                        },
                        cancel: {
                            label: 'Hủy bỏ',
                            className: 'btn--sm bg-dark'
                        }
                    },
                    callback: r => {
                        if (!r) return;
                        document.body.classList.add('waiting');
                        axios.delete(
                            "api/comment/delete/" + id,
                        ).then(res => {
                            if(res.status == 'success') {
                                if (index_parent != null) {
                                    this.comments[index_parent].child[index].approved = true;
                                    this.comments[index_parent].child.splice(index, 1);
                                } else {
                                    this.comments.splice(index, 1);
                                }
                            }
                        }).finally(() => {
                            this.loadingComment = false;
                            document.body.classList.remove('waiting');
                        });
                    }
                });
            },
        },
    });

</script>
